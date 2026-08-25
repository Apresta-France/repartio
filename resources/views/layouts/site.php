<!DOCTYPE html>
<html lang="fr">
<head>
<?php require BASE_PATH . '/resources/views/partials/seo.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/fonts.php'; ?>
</head>
<body>
<div class="page">
  <?php require BASE_PATH . '/resources/views/partials/header.php'; ?>
  <?php if (!empty($breadcrumbs) && empty($hide_crumbs)): ?>
    <?php require BASE_PATH . '/resources/views/partials/breadcrumbs.php'; ?>
  <?php endif; ?>
  <?= $content ?>
  <?php require BASE_PATH . '/resources/views/partials/footer.php'; ?>
</div>
<?php require BASE_PATH . '/resources/views/partials/flash.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/tracking.php'; ?>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
<?php if (!empty($ressources)): ?>
<script src="<?= e(asset('js/ressources.js')) ?>" defer></script>
<?php endif; ?>
<?php if (!empty($builder)): ?>
<script src="<?= e(asset('js/builder.js')) ?>" defer></script>
<?php endif; ?>
<?php if (!empty($showcase)): ?>
<script src="<?= e(asset('js/showcase.js')) ?>" defer></script>
<?php endif; ?>
</body>
</html>
