<?php
/**
 * Locations page — ported from src/routes/locations.tsx (server-side search and
 * filter instead of a client map).
 *
 * @var array<int,array<string,mixed>> $facilities
 * @var string $q
 * @var string $type
 */
$types = [
    '' => 'All types',
    'distribution' => 'Distribution centers',
    'warehouse' => 'Warehouses',
    'pickup' => 'Pickup locations',
    'service' => 'Service centers',
];
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Network</p>
    <h1>Find a SwiftShip location</h1>
    <p>Search warehouses, distribution hubs, pickup points, and service centers by city, state, ZIP, or country.</p>
  </div>
</section>

<div class="container page-body">
  <form class="form-row" method="get" action="/locations">
    <div class="grow">
      <label class="small muted" for="q" style="display:block;margin-bottom:0.35rem;">Search the network</label>
      <input id="q" name="q" type="search" value="<?= e($q) ?>" placeholder="City, state, ZIP, or country">
    </div>
    <div class="fixed">
      <label class="small muted" for="type" style="display:block;margin-bottom:0.35rem;">Type</label>
      <select id="type" name="type">
        <?php foreach ($types as $value => $label): ?>
          <option value="<?= e($value) ?>"<?= $type === $value ? ' selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="fixed" style="align-self:flex-end;">
      <button class="btn btn-block" type="submit">Search</button>
    </div>
  </form>

  <p class="small muted" style="margin-top:1rem;">
    <?= count($facilities) ?> location<?= count($facilities) === 1 ? '' : 's' ?> found.
  </p>

  <div class="grid grid-2" style="margin-top:1.5rem;">
    <?php foreach ($facilities as $facility): ?>
      <div class="card card-tight">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;">
          <h2 class="card-title"><?= e((string) $facility['name']) ?></h2>
          <span class="badge badge-info"><?= e((string) $facility['type']) ?></span>
        </div>
        <p class="small muted" style="margin-top:0.5rem;">
          <?= e((string) $facility['street']) ?><br>
          <?= e((string) $facility['city']) ?>, <?= e((string) $facility['state']) ?> <?= e((string) $facility['postal_code']) ?><br>
          <?= e((string) $facility['country']) ?>
        </p>
        <p class="small" style="margin-top:0.75rem;"><?= e((string) $facility['hours']) ?></p>
        <p class="small muted"><?= e((string) $facility['phone']) ?></p>
        <p class="small muted" style="margin-top:0.5rem;"><?= e((string) $facility['services']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if (!$facilities): ?>
    <p class="center muted" style="margin-top:2rem;">No locations match that search.</p>
  <?php endif; ?>
</div>
