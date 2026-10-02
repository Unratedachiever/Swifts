<?php
/** Track landing page — ported from src/routes/track/index.tsx. */
$tips = [
    ['scan', 'Use the number on your label', 'SwiftShip tracking numbers start with SWF, followed by nine digits.'],
    ['pin', 'Scans post in real time', 'Pickup, sort, linehaul, and delivery events appear as soon as a facility records them.'],
    ['bell', 'Stay notified', 'Sign in to receive delay and delivery notices on your account dashboard.'],
];
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Tracking</p>
    <h1>Follow every scan</h1>
    <p>Enter a SwiftShip tracking number to see live status, facility history, and the route from origin to destination.</p>
  </div>
</section>

<div class="container page-body">
  <div class="card">
    <?php $variant = 'page'; require __DIR__ . '/../partials/track-form.php'; ?>
    <p class="small muted" style="margin-top:0.75rem;">
      Missing a number? Check your shipping confirmation, or <a href="/contact" style="color:var(--teal);">contact the desk</a>.
      Try the demo number <span class="tracking-code">SWF100450231</span>.
    </p>
  </div>

  <div class="grid grid-3" style="margin-top:2.5rem;">
    <?php foreach ($tips as [$icon, $title, $copy]): ?>
      <div class="card card-tight">
        <span class="icon-tile"><?= icon($icon) ?></span>
        <h2 class="card-title" style="margin-top:1rem;"><?= e($title) ?></h2>
        <p class="small muted" style="margin-top:0.25rem;"><?= e($copy) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
