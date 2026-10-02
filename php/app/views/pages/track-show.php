<?php
/**
 * Tracking result — the public timeline for one shipment.
 *
 * @var string $code
 * @var array<string,mixed>|null $shipment
 */
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Tracking</p>
    <h1 class="tracking-code" style="color:var(--paper);font-size:1.9rem;"><?= e($code) ?></h1>
    <?php if ($shipment): ?>
      <p><?= e(SERVICES[(string) $shipment['service_code']]['name'] ?? 'SwiftShip service') ?> ·
        <?= e((string) $shipment['origin']) ?> → <?= e((string) $shipment['destination']) ?></p>
    <?php else: ?>
      <p>We could not find that tracking number.</p>
    <?php endif; ?>
  </div>
</section>

<div class="container page-body">
  <?php if (!$shipment): ?>
    <div class="card">
      <h2 class="card-title">No shipment matches <?= e($code) ?></h2>
      <p class="muted" style="margin-top:0.5rem;">
        Check the number on your label — SwiftShip numbers start with SWF. Need help?
        <a href="/contact" style="color:var(--teal);">Contact the desk</a>.
      </p>
      <a class="btn btn-outline" style="margin-top:1.25rem;" href="/track">Try another number</a>
    </div>
  <?php else: ?>
    <div class="grid grid-2" style="align-items:start;gap:2rem;">
      <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;">
          <h2 class="card-title">Status</h2>
          <span class="badge <?= $shipment['status'] === 'delivered' ? 'badge-success' : ($shipment['status'] === 'exception' ? 'badge-danger' : 'badge-info') ?>">
            <?= e(status_label((string) $shipment['status'])) ?>
          </span>
        </div>
        <dl style="margin:1.25rem 0 0;display:grid;grid-template-columns:auto 1fr;gap:0.6rem 1rem;font-size:0.9rem;">
          <dt class="muted">Service</dt>
          <dd style="margin:0;"><?= e(SERVICES[(string) $shipment['service_code']]['name'] ?? '—') ?></dd>
          <dt class="muted">From</dt>
          <dd style="margin:0;"><?= e((string) $shipment['origin']) ?></dd>
          <dt class="muted">To</dt>
          <dd style="margin:0;"><?= e((string) $shipment['destination']) ?></dd>
          <dt class="muted">Weight</dt>
          <dd style="margin:0;" class="tabular"><?= e(number_format((float) $shipment['weight_lb'], 1)) ?> lb</dd>
          <dt class="muted">Estimated delivery</dt>
          <dd style="margin:0;" class="tabular"><?= e(date('M j, Y', strtotime((string) $shipment['estimated_delivery']))) ?></dd>
        </dl>
        <?php if (!empty($shipment['is_demo'])): ?>
          <div class="alert alert-info" style="margin-top:1.25rem;">
            DEMO shipment — seeded for previewing the tracking experience, not live carrier traffic.
          </div>
        <?php endif; ?>
      </div>

      <div class="card">
        <h2 class="card-title">Scan history</h2>
        <ul class="timeline" style="margin-top:1.25rem;">
          <?php foreach (array_reverse($shipment['events']) as $index => $event): ?>
            <li class="<?= $index === 0 ? 'current' : '' ?>">
              <p class="when"><?= e(date('D, M j · g:i A', strtotime((string) $event['occurred_at']))) ?></p>
              <p class="what"><?= e(status_label((string) $event['status'])) ?></p>
              <p class="where"><?= e((string) $event['location']) ?> — <?= e((string) $event['note']) ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>

  <div style="margin-top:2rem;">
    <a class="btn btn-outline" href="/track">Track another package</a>
  </div>
</div>
