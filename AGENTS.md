# AGENTS.md

Findings from bringing this repo up in the Base44 sandbox. Manifests already
describe the stack, so this file only records what is not obvious.

## Running here

Everything runs in one container via `docker-compose.base44.yml`:

```bash
docker compose -f docker-compose.base44.yml up -d
docker compose -f docker-compose.base44.yml logs -f web
```

- The Vite dev server (TanStack Start) listens on **8080** inside the container
  and is published on host **3000** (the preview port). `npm run dev` already
  passes `--host 0.0.0.0 --port 8080`; `vite.config.ts` pins the same values.
- Dependencies are installed at container start (`npm ci`) into a named volume
  at `/app/node_modules`, because the repo is bind-mounted over `/app`. The
  first boot therefore takes a while; the compose healthcheck allows for it.
- Restart the service after changing `package.json`/`package-lock.json`; source
  edits are picked up by Vite's HMR with no restart.

## Environment

- **No database service and no `DATABASE_URL` on purpose.** `src/lib/db.ts`
  falls back to the embedded PGlite database and applies `migrations/*.sql` on
  the first query. It is **in-memory**: everything (users, shipments) is lost
  when the container restarts. Add a Postgres service and set `DATABASE_URL`
  (then run `npm run db:migrate`) if you want the preview data to survive.
- `BETTER_AUTH_URL` must stay pinned to the preview's public origin
  (`https://3000-${BASE44_PUBLIC_HOST_SUFFIX}` in compose). Otherwise Better
  Auth derives its origin from the request host, which is not in its allowlist
  (`src/lib/auth/preview.ts` only covers `*.grok-sandbox.com`), and every
  email/password sign-up/sign-in fails with **"Invalid origin"**.
- `__VITE_ADDITIONAL_SERVER_ALLOWED_HOSTS` is passed through from the platform
  env; Vite appends it to `server.allowedHosts` so the dev server answers the
  proxied preview hostname. Do not hardcode a resolved host anywhere.
- `VITE_AUTH_ENABLED=true` keeps real sign-in (email/password + the Grok
  provider buttons). Setting it to `"false"` switches the app to a single
  dev user (`requireUserId`), which only works while `DATABASE_URL` is unset.
- Federated "Continue with Google/X" sign-in goes through the external Grok auth
  broker and needs per-app `GROK_AUTH_ISSUER` / `GROK_AUTH_CLIENT_ID` /
  `GROK_AUTH_CLIENT_SECRET`. Those are **not** available here — use email and
  password. `GROK_PROJECT_ID` must stay unset (it marks workspace preview mode).

## Verifying

```bash
curl -fsS http://localhost:3000/ | head            # SSR marketing page
curl -fsS -o /dev/null -w '%{http_code}\n' http://localhost:3000/login
npm run typecheck
npm test        # node --test scripts/*.test.mjs + app-data/auth unit tests
```

Payments are demo/stub code (card `last4` only), so checkout needs no provider
keys. First admin: register, then **Account → Activate staff access**.
