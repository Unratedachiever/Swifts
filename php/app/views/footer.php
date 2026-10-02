<?php
$columns = [
    'Ship' => [
        ['/ship', 'Create a shipment'],
        ['/quote', 'Get a quote'],
        ['/track', 'Track a package'],
        ['/pricing', 'Rates'],
    ],
    'Network' => [
        ['/services', 'Services'],
        ['/locations', 'Locations'],
        ['/freight', 'Freight'],
        ['/returns', 'Returns'],
    ],
    'Company' => [
        ['/about', 'About'],
        ['/business', 'Business'],
        ['/contact', 'Contact'],
        ['/help', 'Help center'],
    ],
];
?>
<footer class="site-footer">
  <div class="container cols">
    <div class="about">
      <a class="brand" href="/">
        <?php require __DIR__ . '/mark.php'; ?>
        <span class="brand-text"><strong>SwiftShip</strong><span>Logistics</span></span>
      </a>
      <p>Fast, secure worldwide delivery. From a single parcel to a freight program, SwiftShip moves what matters — and shows you every mile.</p>
      <p class="small" style="margin-top:1.4rem;font-weight:600;"><?= e(BRAND_PHONE) ?></p>
      <p class="small" style="color:color-mix(in oklab, var(--paper) 60%, transparent);"><?= e(BRAND_HOURS) ?></p>
    </div>
    <?php foreach ($columns as $title => $links): ?>
      <div>
        <h3><?= e($title) ?></h3>
        <ul>
          <?php foreach ($links as [$href, $label]): ?>
            <li><a href="<?= e($href) ?>"><?= e($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="legal">
    <div class="container inner">
      <p>© <?= e(date('Y')) ?> SwiftShip Logistics. All rights reserved.</p>
      <p>Secure checkout · Full card numbers are never stored.</p>
    </div>
  </div>
</footer>
