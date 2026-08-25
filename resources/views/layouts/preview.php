<!DOCTYPE html>
<html lang="fr">
<head>
<?php require BASE_PATH . '/resources/views/partials/seo-private.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/fonts.php'; ?>
</head>
<body>
<div class="app-shell is-builder">
  <div class="app-main">
    <?= $content ?>
  </div>
</div>
<?php require BASE_PATH . '/resources/views/partials/tracking.php'; ?>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
<script src="<?= e(asset('js/builder.js')) ?>" defer></script>
</body>
</html>
