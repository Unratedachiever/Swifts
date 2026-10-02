<?php
/**
 * Contact page — ported from src/routes/contact.tsx.
 *
 * @var array<string,string> $errors
 */
$errors = $errors ?? [];
$topics = [
    'shipment' => 'Shipment status',
    'billing' => 'Billing',
    'pickup' => 'Pickup request',
    'claims' => 'Claims',
    'other' => 'Something else',
];
$selectedTopic = old('topic', 'shipment');
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Contact</p>
    <h1>Talk to SwiftShip</h1>
    <p>Billing, claims, pickup windows, or a delayed shipment — send a note or call the desk.</p>
  </div>
</section>

<div class="container page-body">
  <div class="grid grid-2" style="align-items:start;gap:2rem;">
    <div class="stack">
      <div class="card">
        <p class="kicker muted" style="color:var(--mist);">Customer care</p>
        <p class="section-title" style="margin-top:0.5rem;font-size:1.5rem;"><?= e(BRAND_PHONE) ?></p>
        <p class="small muted" style="margin-top:0.25rem;"><?= e(BRAND_HOURS) ?></p>
      </div>
      <div class="card">
        <p class="kicker muted" style="color:var(--mist);">Headquarters</p>
        <p style="margin-top:0.5rem;font-weight:600;">4100 E Bandini Blvd</p>
        <p class="small muted">Los Angeles, CA 90040</p>
      </div>
    </div>

    <div class="card">
      <h2 class="card-title">Send a message</h2>
      <p class="small muted" style="margin-top:0.25rem;margin-bottom:1rem;">A specialist replies on business days.</p>
      <form method="post" action="/contact">
        <?= csrf_field() ?>
        <div class="field">
          <label for="name">Name</label>
          <input id="name" name="name" type="text" value="<?= e(old('name')) ?>" required>
          <?php if (isset($errors['name'])): ?><p class="field-error"><?= e($errors['name']) ?></p><?php endif; ?>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="<?= e(old('email')) ?>" required>
          <?php if (isset($errors['email'])): ?><p class="field-error"><?= e($errors['email']) ?></p><?php endif; ?>
        </div>
        <div class="field">
          <label for="topic">Topic</label>
          <select id="topic" name="topic">
            <?php foreach ($topics as $value => $label): ?>
              <option value="<?= e($value) ?>"<?= $selectedTopic === $value ? ' selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="message">Message</label>
          <textarea id="message" name="message" required minlength="10"><?= e(old('message')) ?></textarea>
          <?php if (isset($errors['message'])): ?><p class="field-error"><?= e($errors['message']) ?></p><?php endif; ?>
        </div>
        <button class="btn" type="submit">Send</button>
      </form>
    </div>
  </div>
</div>
