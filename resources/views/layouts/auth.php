<!DOCTYPE html>
<html lang="fr">
<head>
<?php require BASE_PATH . '/resources/views/partials/seo-private.php'; ?>
  <meta name="referrer" content="no-referrer">
<?php require BASE_PATH . '/resources/views/partials/fonts.php'; ?>
</head>
<body>
<?= $content ?>
<?php require BASE_PATH . '/resources/views/partials/flash.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/tracking.php'; ?>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
