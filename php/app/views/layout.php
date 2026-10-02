<?php
/**
 * Site chrome.
 *
 * @var string $content
 * @var string|null $title
 * @var string|null $description
 */
$title = isset($title) && $title !== '' ? $title . ' · ' . BRAND_NAME : BRAND_NAME;
$description = $description ?? 'SwiftShip Logistics — ship anything, track everything. Fast, secure worldwide delivery.';
$notice = take_flash('success');
$error = take_flash('error');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($description) ?>">
  <meta name="theme-color" content="#0c1424">
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap">
  <link rel="stylesheet" href="<?= e(asset('assets/styles.css')) ?>">
</head>
<body>
  <?php require __DIR__ . '/header.php'; ?>
  <main>
    <?php if ($notice || $error): ?>
      <div class="container" style="padding-top:1.25rem;">
        <?php if ($notice): ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
      </div>
    <?php endif; ?>
    <?= $content ?>
  </main>
  <?php require __DIR__ . '/footer.php'; ?>
  <script src="<?= e(asset('assets/app.js')) ?>" defer></script>
</body>
</html>
