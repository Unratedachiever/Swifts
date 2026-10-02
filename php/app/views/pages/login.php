<?php
/**
 * Log in — ported from src/routes/login.tsx (email + password).
 *
 * @var string|null $error
 */
$error = $error ?? null;
?>
<div class="container" style="padding:3.5rem 0;">
  <div class="grid grid-2" style="align-items:center;gap:2.5rem;">
    <div>
      <p class="kicker">Customer portal</p>
      <h1 class="section-title" style="margin-top:0.75rem;">Welcome back</h1>
      <p class="muted" style="margin-top:0.75rem;max-width:26rem;">
        Sign in to manage shipments, saved addresses, billing, and delivery notifications.
      </p>
    </div>
    <div class="card" style="max-width:28rem;width:100%;justify-self:center;">
      <h2 class="card-title">Log in</h2>
      <p class="small muted" style="margin-top:0.25rem;margin-bottom:1.25rem;">Use the email and password for your account.</p>

      <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom:1rem;"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" action="/login">
        <?= csrf_field() ?>
        <div class="field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" autocomplete="email" value="<?= e(old('email')) ?>" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" autocomplete="current-password" required minlength="8">
        </div>
        <button class="btn btn-block" type="submit">Sign in</button>
      </form>

      <p class="small muted" style="margin-top:1.25rem;">
        New to SwiftShip? <a href="/register" style="color:var(--teal);font-weight:600;">Create an account</a>
      </p>
    </div>
  </div>
</div>
