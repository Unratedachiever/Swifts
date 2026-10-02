# AGENTS.md

Findings from bringing this repo up in the Base44 sandbox. Manifests describe the
stack, so this file records only what is not obvious.

## What runs in the preview

Two apps live in this repo:

| Where | What | Preview |
| --- | --- | --- |
| `php/` | **The PHP port** (plain PHP + PDO + SQLite) — this is what port 3000 serves | http://localhost:3000 |
| `src/`, `server/`, `scripts/` | The original TanStack Start (React 19) app, kept runnable | opt-in, host port 3001 |

```bash
docker compose -f docker-compose.base44.yml up -d                      # PHP app + Mailpit
docker compose -f docker-compose.base44.yml --profile node up -d node  # legacy Node app on 3001
docker compose -f docker-compose.base44.yml logs -f php
```

- PHP is served by the built-in dev server
  (`php -S 0.0.0.0:8080 -t /app/php/public /app/php/public/index.php`), reading the
  bind-mounted source on every request — no build step, no dependency install.
  `php/public/index.php` is the front controller and route table.
- The Node service installs dependencies with `npm ci` at startup into a named
  volume (the bind mount would hide them), which is why its first boot is slow.

## Data

- SQLite at `/data/app.sqlite` (volume `php-data`; `DB_PATH` overrides it).
  `php/app/db.php` creates the schema and seeds the 16 facility locations plus two
  DEMO shipments (`SWF100450231` in transit, `SWF100450875` delivered) on first use.
- Passwords use `password_hash()`; sessions are rows in `sessions` with an
  httpOnly `swiftship_session` cookie. No external database is needed.

## Email (the welcome mail on sign-up)

- `php/app/mail.php` is a dependency-free SMTP client (STARTTLS/SSL + AUTH LOGIN),
  driven entirely by env vars.
- Development default is **Mailpit** (`axllent/mailpit`, host port 8025): every
  sign-up email lands in that inbox — open
  `https://8025-${BASE44_PUBLIC_HOST_SUFFIX}` (or http://localhost:8025).
- For real delivery, set `SMTP_HOST`/`SMTP_PORT`/`SMTP_USER`/`SMTP_PASS`/
  `SMTP_SECURE`/`MAIL_FROM` on the Base44 dashboard; they arrive via
  `/run/base44/app.env`, which compose lists LAST so the values win over
  `.env.base44-defaults`. `MAIL_TRANSPORT=log` writes rendered email to
  `php/data/mail/` instead of sending.
- The email logo is a PNG rendered from the app's own SVG mark at
  `php/public/assets/brand/mark.png`; it is referenced by absolute URL from
  `APP_URL`, so the inbox can load it.

## Verifying

```bash
curl -fsS http://localhost:3000/ | head                 # public home page
curl -fsS -o /dev/null -w '%{http_code}\n' http://localhost:3000/track/SWF100450231
curl -fsS http://localhost:8025/api/v1/messages         # captured welcome emails
```

Register in the UI, then confirm the message arrived in Mailpit with the subject
"Welcome to SwiftShip — your account is ready".

## Not ported yet

`/ship`, `/quote`, `/checkout` and `/admin` render a "port in progress" page;
the account dashboard lists shipments but booking is still to come. The original
React app still has all of these — run it with the `node` profile to compare.
