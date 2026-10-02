<?php
/** Services page — ported from src/routes/services.tsx. */
$extras = [
    ['/freight', 'Freight', 'Pallets and LTL with scheduled appointments and liftgate options.'],
    ['/returns', 'Returns', 'Prepaid return labels and hold-for-pickup windows.'],
    ['/business', 'Business', 'Volume rates, pickup programs, and a shared operations inbox.'],
];
?>
<section class="page-header">
  <div class="container inner">
    <p class="kicker" style="color:var(--teal-2);">Services</p>
    <h1>One network, four speeds</h1>
    <p>Pick a product by time, or start with a quote and we’ll show every option that can make the window.</p>
  </div>
</section>

<div class="container page-body">
  <div class="grid grid-2">
    <?php foreach ($services as $service): ?>
      <div class="card">
        <p class="kicker"><?= e($service['tagline']) ?></p>
        <h2 class="section-title" style="margin-top:0.5rem;font-size:1.6rem;"><?= e($service['name']) ?></h2>
        <p class="muted" style="margin-top:0.5rem;"><?= e($service['description']) ?></p>
        <p class="small muted" style="margin-top:1rem;"><?= e($service['transit']) ?></p>
        <a class="btn btn-outline" style="margin-top:1.25rem;" href="/quote">Get a <?= e($service['name']) ?> quote</a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="media-figure" style="margin-top:2.5rem;">
    <img src="/images/porch-delivery.jpg" alt="Package delivered to a porch" style="max-height:20rem;width:100%;object-fit:cover;">
  </div>

  <div class="grid grid-3" style="margin-top:2rem;">
    <?php foreach ($extras as [$href, $title, $copy]): ?>
      <a class="card-link" href="<?= e($href) ?>">
        <h3 class="card-title"><?= e($title) ?></h3>
        <p class="small muted" style="margin-top:0.5rem;"><?= e($copy) ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</div>
