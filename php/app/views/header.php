<?php
require_once __DIR__ . '/../icons.php';

$user = current_user();
$nav = [
    ['/' , 'Home'],
    ['/track', 'Track'],
    ['/ship', 'Ship Now'],
    ['/services', 'Services'],
    ['/locations', 'Locations'],
    ['/pricing', 'Pricing'],
    ['/contact', 'Contact'],
];
?>
<header class="site-header">
  <div class="container inner">
    <a class="brand" href="/">
      <?php require __DIR__ . '/mark.php'; ?>
      <span class="brand-text"><strong>SwiftShip</strong><span>Logistics</span></span>
    </a>

    <nav class="site-nav" aria-label="Main">
      <?php foreach ($nav as [$href, $label]): ?>
        <a href="<?= e($href) ?>"<?= nav_active($href) ? ' class="active" aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="header-actions">
      <button type="button" class="btn btn-ghost btn-sm desktop-only" data-theme-toggle aria-label="Switch to dark mode">◐</button>
      <div class="desktop-only">
        <?php if ($user): ?>
          <a class="btn btn-outline btn-sm" href="/account">Account</a>
          <form method="post" action="/logout">
            <?= csrf_field() ?>
            <button class="btn btn-ghost btn-sm" type="submit">Sign out</button>
          </form>
        <?php else: ?>
          <a class="btn btn-ghost btn-sm" href="/login">Log in</a>
          <a class="btn btn-sm" href="/register">Create account</a>
        <?php endif; ?>
      </div>
      <button type="button" class="menu-toggle" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu">☰</button>
    </div>
  </div>

  <div class="mobile-nav" id="mobile-nav" hidden>
    <nav aria-label="Mobile">
      <?php foreach ($nav as [$href, $label]): ?>
        <a href="<?= e($href) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="actions">
      <?php if ($user): ?>
        <a class="btn" href="/account">Account</a>
      <?php else: ?>
        <a class="btn btn-outline" href="/login">Log in</a>
        <a class="btn" href="/register">Create account</a>
      <?php endif; ?>
    </div>
  </div>
</header>
