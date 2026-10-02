<?php
/** Help center — ported from src/routes/help.tsx. */
$items = [
    ['Where is my package?', 'Open Track and enter the SwiftShip number from your label or confirmation email. The page refreshes as operations posts each new scan.'],
    ['How do I create a shipment?', 'Use Ship to enter origin, destination, and package details. Choose a service, pay the label, and you receive a tracking number immediately — with or without an account.'],
    ['What if my shipment is delayed?', 'Exception statuses appear on the tracking timeline with the latest facility and a revised delivery window. Signed-in customers also get an in-app notice.'],
    ['How do payments work?', 'Pay by card, invoice, or the method on your account. We never store full card numbers — only brand and last four digits for your receipt.'],
    ['Can my team use the operations console?', 'Yes. The first signed-in operator can activate staff access from Account. Additional operators are promoted from the customers list.'],
    ['How do I file a claim or request a pickup?', 'Use Contact and choose Claims or Pickup. The desk is staffed Mon–Fri 6:00–21:00 PT and Saturday 8:00–16:00 PT.'],
];
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Help center</p>
    <h1>Guides for shippers and operators</h1>
  </div>
</section>

<div class="container" style="padding:3rem 0;max-width:48rem;">
  <?php foreach ($items as [$q, $a]): ?>
    <details class="faq">
      <summary><?= e($q) ?></summary>
      <p><?= e($a) ?></p>
    </details>
  <?php endforeach; ?>
  <p class="small muted" style="margin-top:2rem;">
    Need a person? <a href="/contact" style="color:var(--teal);">Contact the desk</a>.
  </p>
</div>
