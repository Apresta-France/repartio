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
<?php if (!empty($demo_video)): ?>
<div class="demo-modal" data-demo-modal hidden>
  <div class="demo-modal-backdrop" data-demo-dismiss></div>
  <div class="demo-modal-card" id="demo-modal" role="dialog" aria-modal="true" aria-labelledby="demo-modal-title">
    <h2 id="demo-modal-title" class="visually-hidden">Démo repartio</h2>
    <button type="button" class="demo-modal-close" data-demo-dismiss aria-label="Fermer">×</button>
    <video class="demo-modal-video" data-demo-video controls playsinline preload="none">
      <source data-src="<?= e(url('/repartio.mp4')) ?>" type="video/mp4">
    </video>
  </div>
</div>
<?php endif; ?>
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
