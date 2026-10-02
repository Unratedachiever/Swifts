<?php
/**
 * Create account — ported from src/routes/register.tsx.
 *
 * @var array<string,string> $errors
 */
$errors = $errors ?? [];
?>
<div class="container" style="padding:3.5rem 0;">
  <div class="grid grid-2" style="align-items:center;gap:2.5rem;">
    <div>
      <p class="kicker">Create account</p>
      <h1 class="section-title" style="margin-top:0.75rem;">Ship with a profile</h1>
      <p class="muted" style="margin-top:0.75rem;max-width:26rem;">
        Save addresses, pay invoices, and see every active shipment in one place. Staff can later
        enable the operations console from the first account.
      </p>
      <p class="small muted" style="margin-top:1.25rem;">
        We’ll email you a welcome note with your account details and a link back to the desk.
      </p>
    </div>
    <div class="card" style="max-width:28rem;width:100%;justify-self:center;">
      <h2 class="card-title">Create account</h2>
      <p class="small muted" style="margin-top:0.25rem;margin-bottom:1.25rem;">Free to open. No card required.</p>

      <form method="post" action="/register">
        <?= csrf_field() ?>
        <div class="field">
          <label for="name">Full name</label>
          <input id="name" name="name" type="text" autocomplete="name" value="<?= e(old('name')) ?>" required minlength="2">
          <?php if (isset($errors['name'])): ?><p class="field-error"><?= e($errors['name']) ?></p><?php endif; ?>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" autocomplete="email" value="<?= e(old('email')) ?>" required>
          <?php if (isset($errors['email'])): ?><p class="field-error"><?= e($errors['email']) ?></p><?php endif; ?>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8">
          <?php if (isset($errors['password'])): ?><p class="field-error"><?= e($errors['password']) ?></p><?php endif; ?>
        </div>
        <button class="btn btn-block" type="submit">Create account</button>
      </form>

      <p class="small muted" style="margin-top:1.25rem;">
        Already have an account? <a href="/login" style="color:var(--teal);font-weight:600;">Log in</a>
      </p>
    </div>
  </div>
</div>
