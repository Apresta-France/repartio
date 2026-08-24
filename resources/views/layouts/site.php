<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'repartio') ?> — repartio.fr</title>
  <link rel="icon" href="<?= e(asset('img/logo.png')) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>
<div class="page">
  <?php require BASE_PATH . '/resources/views/partials/header.php'; ?>
  <?= $content ?>
  <?php require BASE_PATH . '/resources/views/partials/footer.php'; ?>
</div>
<?php require BASE_PATH . '/resources/views/partials/flash.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/tracking.php'; ?>
<script src="<?= e(asset('js/app.js')) ?>"></script>
<?php if (!empty($ressources)): ?>
<script src="<?= e(asset('js/ressources.js')) ?>"></script>
<?php endif; ?>
<?php if (!empty($builder)): ?>
<script src="<?= e(asset('js/builder.js')) ?>"></script>
<?php endif; ?>
<?php if (!empty($showcase)): ?>
<script src="<?= e(asset('js/showcase.js')) ?>"></script>
<?php endif; ?>
</body>
</html>
