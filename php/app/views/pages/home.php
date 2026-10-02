<?php
/** Home page — ported from src/routes/index.tsx. */

$stats = [
    ['184M+', 'Shipments delivered'],
    ['220', 'Countries served'],
    ['86', 'Distribution centers'],
    ['99.4%', 'On-time satisfaction'],
];

$actions = [
    ['/track', 'Track a shipment', 'Live status, scan history, and route.', 'package-search'],
    ['/ship', 'Create a shipment', 'From a single parcel to a freight pallet.', 'box'],
    ['/quote', 'Get a quote', 'Compare Ground, Express, Overnight, Worldwide.', 'clock'],
    ['/locations', 'Find a location', 'Hubs, drop-off points, and holds.', 'pin'],
];

$faqs = [
    ['How do I track a package?', 'Enter your SwiftShip tracking number (it starts with SWF) on the homepage or Track page. Status updates as soon as a facility posts a scan.'],
    ['What if my shipment is delayed?', 'Delayed and exception statuses appear on the tracking timeline with the latest facility and a revised estimated delivery. Signed-in customers also receive an in-app notice.'],
    ['Can I ship without an account?', 'Yes. Guest checkout creates a tracking number instantly. An account saves addresses, billing methods, and history.'],
    ['How are rates calculated?', 'Quotes combine billable weight (actual vs dimensional), distance between origin and destination, and service speed. Fuel and handling appear as separate fees.'],
    ['Do you store card numbers?', 'No. We never store full card numbers. Checkout keeps only the last four digits for your receipt.'],
];

$stories = [
    ['We moved our replenishment to SwiftShip Express and cut two days off every West Coast restock.', 'Elena Voss', 'Head of Logistics, Northwind Studio'],
    ['The tracking timeline is what we show customers. When a status changes, they see it without calling us.', 'Marcus Hale', 'Operations, Harbor House Kitchen'],
    ['International used to mean a spreadsheet. Brokerage included on Worldwide is the reason we switched.', 'Priya Shah', 'Founder, Kite Paper Co.'],
];

$steps = [
    ['box', 'Create', 'Label, rate, and pickup window in one booking.'],
    ['truck', 'Collect', 'Courier scan at the door or drop-off counter.'],
    ['building', 'Sort', 'Facility processing with a departure event.'],
    ['globe', 'Linehaul', 'Air and ground, including customs when needed.'],
    ['pin', 'Arrive', 'Destination facility, then last-mile assignment.'],
    ['shield', 'Deliver', 'Proof of delivery with a signed scan.'],
];
?>

<section class="hero">
  <img class="hero-bg" src="/images/hub-dawn.jpg" alt="">
  <div class="hero-overlay"></div>
  <div class="container hero-inner">
    <div>
      <p class="kicker" style="color:var(--teal-2);">Worldwide logistics</p>
      <h1>Ship anything. Track everything.</h1>
      <p class="hero-lede">
        Fast, secure delivery across 220 countries. From a labeled envelope to a freight program,
        SwiftShip keeps every scan, delay, and proof of delivery in one place.
      </p>
    </div>
    <div class="hero-card">
      <p style="font-weight:600;">Track a shipment</p>
      <p class="small" style="margin-top:0.25rem;color:color-mix(in oklab, var(--paper) 60%, transparent);">Use a SwiftShip tracking number (SWF…)</p>
      <div style="margin-top:1rem;">
        <?php $variant = 'hero'; require __DIR__ . '/../partials/track-form.php'; ?>
      </div>
      <div style="margin-top:1rem;display:flex;flex-wrap:wrap;gap:0.5rem;">
        <a class="btn btn-linen btn-sm" href="/ship">Ship a package</a>
        <a class="btn btn-outline btn-sm" style="border-color:rgb(255 255 255 / 0.24);color:var(--paper);" href="/quote">Compare rates</a>
      </div>
    </div>
  </div>
</section>

<section class="container" style="padding-top:3rem;">
  <div class="grid grid-4">
    <?php foreach ($actions as [$href, $title, $copy, $icon]): ?>
      <a class="card-link" href="<?= e($href) ?>">
        <span class="icon-tile"><?= icon($icon) ?></span>
        <h2 class="card-title" style="margin-top:1rem;"><?= e($title) ?></h2>
        <p class="small muted" style="margin-top:0.25rem;"><?= e($copy) ?></p>
        <span class="small" style="margin-top:1rem;display:inline-block;color:var(--teal);font-weight:600;">Continue →</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="band" style="margin-top:3rem;">
  <div class="container inner">
    <?php foreach ($stats as [$value, $label]): ?>
      <div>
        <p class="stat-value tabular"><?= e($value) ?></p>
        <p class="small muted" style="margin-top:0.25rem;"><?= e($label) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="container" style="padding:4rem 0;">
  <div class="grid grid-2" style="align-items:center;gap:2.5rem;">
    <div class="media-figure">
      <img src="/images/sortation.jpg" alt="Parcel sortation warehouse">
    </div>
    <div>
      <p class="kicker">Services</p>
      <h2 class="section-title" style="margin-top:0.5rem;">A network built for certainty</h2>
      <p class="muted" style="margin-top:0.75rem;">
        One operating picture from first-mile pickup to the porch. Choose a service, or let the
        quote engine pick based on time and cost.
      </p>
      <div class="grid grid-2" style="margin-top:1.5rem;">
        <?php foreach (SERVICES as $service): ?>
          <a class="card-link" href="/services">
            <h3 style="font-family:var(--font-sans);font-size:1rem;font-weight:600;"><?= e(explode(' ', $service['name'])[1] ?? $service['name']) ?></h3>
            <p class="small muted" style="margin-top:0.25rem;"><?= e($service['description']) ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--navy);color:var(--paper);">
  <div class="container" style="padding:4rem 0;display:grid;gap:2.5rem;">
    <div>
      <h2 class="section-title">How a shipment moves</h2>
      <p style="margin-top:0.75rem;color:color-mix(in oklab, var(--paper) 70%, transparent);max-width:32rem;">
        Eight visible steps. Status updates land on the tracking page as soon as operations posts them.
      </p>
    </div>
    <ol class="steps">
      <?php foreach ($steps as $i => [$icon, $title, $copy]): ?>
        <li>
          <span class="icon-tile" style="background:rgb(255 255 255 / 0.06);color:var(--teal-2);"><?= icon($icon) ?></span>
          <div>
            <p style="font-weight:600;">
              <span class="tabular" style="color:color-mix(in oklab, var(--paper) 45%, transparent);"><?= e(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?> · </span><?= e($title) ?>
            </p>
            <p class="small" style="margin-top:0.25rem;color:color-mix(in oklab, var(--paper) 66%, transparent);"><?= e($copy) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="container" style="padding:4rem 0;">
  <h2 class="section-title">Operators, not slogans</h2>
  <div class="grid grid-3" style="margin-top:2rem;">
    <?php foreach ($stories as [$quote, $name, $role]): ?>
      <div class="card">
        <p>“<?= e($quote) ?>”</p>
        <p class="small" style="margin-top:1.25rem;font-weight:600;"><?= e($name) ?></p>
        <p class="small muted"><?= e($role) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="band">
  <div class="container" style="padding:4rem 0;display:grid;gap:2.5rem;">
    <div>
      <h2 class="section-title">Questions, answered</h2>
      <p class="muted" style="margin-top:0.75rem;">Still stuck? The help center and contact desk are staffed on business days.</p>
      <a class="btn btn-outline" style="margin-top:1.5rem;" href="/help">Open help center</a>
    </div>
    <div>
      <?php foreach ($faqs as [$q, $a]): ?>
        <details class="faq">
          <summary><?= e($q) ?></summary>
          <p><?= e($a) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta">
  <img class="hero-bg" src="/images/van-sunrise.jpg" alt="">
  <div class="hero-overlay" style="background:rgb(12 20 36 / 0.75);"></div>
  <div class="container inner">
    <h2>Ready to move it?</h2>
    <p style="max-width:32rem;color:color-mix(in oklab, var(--paper) 75%, transparent);">
      Get a rate in seconds, or hand us a tracking number and we’ll show you the network.
    </p>
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
      <a class="btn btn-linen" href="/ship">Ship now</a>
      <a class="btn btn-outline" style="border-color:rgb(255 255 255 / 0.24);color:var(--paper);" href="/quote">Get a quote</a>
    </div>
  </div>
</section>
