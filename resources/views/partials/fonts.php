<?php
$jakarta = BASE_PATH . '/public/assets/fonts/plus-jakarta-sans-500-latin.woff2';
$mono = BASE_PATH . '/public/assets/fonts/ibm-plex-mono-500-latin.woff2';
$selfHosted = is_file($jakarta);
?>
<?php if ($selfHosted): ?>
  <link rel="preload" href="<?= e(asset('fonts/plus-jakarta-sans-500-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?= e(asset('fonts/plus-jakarta-sans-700-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <?php if (is_file($mono)): ?>
  <link rel="preload" href="<?= e(asset('fonts/ibm-plex-mono-500-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <?php endif; ?>
<?php else: ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<?php endif; ?>
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
