<!DOCTYPE html>
<html lang="fr">
<head>
<?php require BASE_PATH . '/resources/views/partials/seo-private.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/fonts.php'; ?>
</head>
<body>
<div class="app-shell<?= !empty($builder) ? ' is-builder' : '' ?>">
  <?php if (empty($builder)): ?>
    <header class="app-mobile-bar">
      <button type="button" class="nav-toggle" data-sidebar-toggle aria-expanded="false" aria-controls="app-sidebar">
        <span class="nav-toggle-bars" aria-hidden="true"><i></i><i></i><i></i></span>
        <span class="visually-hidden">Menu</span>
      </button>
      <a href="<?= e(url('/app')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
      <form method="post" action="<?= e(url('/app/circuits/nouveau')) ?>" style="margin-left:auto;"><?= csrf_field() ?><button class="btn btn-orange" type="submit" style="padding:8px 12px;font-size:13px;min-height:0;">+ Circuit</button></form>
    </header>
    <div class="sidebar-backdrop" data-sidebar-close hidden></div>
    <?php require BASE_PATH . '/resources/views/partials/sidebar.php'; ?>
  <?php endif; ?>
  <div class="app-main">
    <?= $content ?>
  </div>
</div>
<?php require BASE_PATH . '/resources/views/partials/flash.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/tracking.php'; ?>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
<?php if (!empty($builder)): ?>
<script src="<?= e(asset('js/builder.js')) ?>" defer></script>
<?php endif; ?>
</body>
</html>
