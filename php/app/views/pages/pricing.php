<?php
/** Pricing page — ported from src/routes/pricing.tsx. */
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Pricing</p>
    <h1>Transparent rates, not a maze</h1>
    <p>Every quote starts from a published base and per-pound rate, then applies distance, dimensional weight, tax, and a fuel surcharge.</p>
  </div>
</section>

<div class="container page-body">
  <div class="table-wrap">
    <table class="data">
      <thead>
        <tr>
          <th>Service</th>
          <th>Transit</th>
          <th>Base</th>
          <th>Per lb</th>
          <th>Best for</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($services as $service): ?>
          <tr>
            <td style="font-weight:600;"><?= e($service['name']) ?></td>
            <td class="tabular"><?= e($service['transit']) ?></td>
            <td class="tabular"><?= e(money((float) $service['base'])) ?></td>
            <td class="tabular"><?= e(money((float) $service['per_lb'])) ?></td>
            <td class="muted"><?= e($service['tagline']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="small muted" style="margin-top:1rem;">
    Dimensional weight uses L × W × H ÷ 139. Tax is 7.5%. Fuel is 4.2% of subtotal plus a $4.95 handling fee.
  </p>
  <a class="btn" style="margin-top:1.5rem;" href="/quote">Get a live quote</a>
</div>
