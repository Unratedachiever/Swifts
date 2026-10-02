<?php
/**
 * Account dashboard — the signed-in view (shipment list is empty until the
 * booking flow is ported).
 *
 * @var array<string,mixed> $user
 * @var array<int,array<string,mixed>> $shipments
 */
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Account</p>
    <h1>Hello, <?= e((string) $user['name']) ?></h1>
    <p>Your shipments, notices, and billing live here.</p>
  </div>
</section>

<div class="container page-body">
  <div class="grid grid-2" style="align-items:start;gap:1.5rem;">
    <div class="card">
      <h2 class="card-title">Account details</h2>
      <dl style="margin:1rem 0 0;display:grid;grid-template-columns:auto 1fr;gap:0.6rem 1rem;font-size:0.9rem;">
        <dt class="muted">Name</dt>
        <dd style="margin:0;"><?= e((string) $user['name']) ?></dd>
        <dt class="muted">Email</dt>
        <dd style="margin:0;"><?= e((string) $user['email']) ?></dd>
        <dt class="muted">Member since</dt>
        <dd style="margin:0;" class="tabular"><?= e(date('M j, Y', strtotime((string) $user['created_at']))) ?></dd>
        <dt class="muted">Staff access</dt>
        <dd style="margin:0;"><?= ((int) $user['is_staff']) === 1 ? 'Enabled' : 'Not enabled' ?></dd>
      </dl>
      <form method="post" action="/logout" style="margin-top:1.5rem;">
        <?= csrf_field() ?>
        <button class="btn btn-outline btn-sm" type="submit">Sign out</button>
      </form>
    </div>

    <div class="card">
      <h2 class="card-title">Active shipments</h2>
      <?php if (!$shipments): ?>
        <p class="muted" style="margin-top:0.75rem;">
          No shipments on your account yet. Book one from <a href="/ship" style="color:var(--teal);">Ship Now</a>
          — or follow a demo shipment from <a href="/track/SWF100450231" style="color:var(--teal);">tracking</a>.
        </p>
      <?php else: ?>
        <div class="table-wrap" style="margin-top:1rem;">
          <table class="data">
            <thead><tr><th>Tracking</th><th>Route</th><th>Status</th></tr></thead>
            <tbody>
              <?php foreach ($shipments as $shipment): ?>
                <tr>
                  <td><a class="tracking-code" href="/track/<?= e((string) $shipment['tracking_number']) ?>"><?= e((string) $shipment['tracking_number']) ?></a></td>
                  <td class="muted"><?= e((string) $shipment['origin']) ?> → <?= e((string) $shipment['destination']) ?></td>
                  <td><span class="badge badge-info"><?= e(status_label((string) $shipment['status'])) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
