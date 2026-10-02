<?php
/** Returns page — ported from src/routes/returns.tsx. */
$steps = [
    ['swap', 'Reverse the addresses', 'Open Ship Now and set the original recipient as the sender. Use the original sender as the return destination.'],
    ['box', 'Choose Ground unless urgent', 'Most returns move on SwiftShip Ground. Use Express only when the merchant requires a faster window.'],
    ['pin', 'Drop at a pickup point', 'Print your label, pack securely, and drop at any SwiftShip pickup or service center. Hold-at-location is available for merchant returns.'],
    ['shield', 'Track the return', 'The return gets its own SWF tracking number. Share it with the merchant so both sides see the same timeline.'],
];
$faqs = [
    ['Do I need the original tracking number?', 'Helpful, but not required. Reference it in the package description so ops and the merchant can reconcile the return.'],
    ['Can I hold a return at a location?', 'Yes. Choose a pickup point on the Locations page and note it in delivery instructions, or set hold preferences under Account → Delivery prefs.'],
    ['Are returns insured?', 'Declared value works the same as outbound shipments. Enter the merchandise value when creating the return label.'],
];
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Returns</p>
    <h1>Send it back on the same network</h1>
    <p>Create a return shipment with the original recipient as the sender. Hold-at-location is available at pickup points.</p>
  </div>
</section>

<div class="container" style="padding:3rem 0;">
  <div class="grid grid-2">
    <?php foreach ($steps as [$icon, $title, $copy]): ?>
      <div class="card" style="display:flex;gap:1rem;">
        <span class="icon-tile" style="flex:none;"><?= icon($icon) ?></span>
        <div>
          <h2 style="font-family:var(--font-sans);font-size:1rem;font-weight:600;"><?= e($title) ?></h2>
          <p class="small muted" style="margin-top:0.25rem;"><?= e($copy) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div style="margin-top:2rem;display:flex;flex-wrap:wrap;gap:0.75rem;">
    <a class="btn" href="/ship">Create a return</a>
    <a class="btn btn-outline" href="/locations">Find a drop-off</a>
    <a class="btn btn-ghost" href="/account">Delivery preferences</a>
  </div>

  <div class="card" style="margin-top:3rem;">
    <?php foreach ($faqs as [$q, $a]): ?>
      <details class="faq">
        <summary><?= e($q) ?></summary>
        <p><?= e($a) ?></p>
      </details>
    <?php endforeach; ?>
  </div>
</div>
