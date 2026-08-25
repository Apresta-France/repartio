<!DOCTYPE html>
<html lang="fr">
<head>
<?php require BASE_PATH . '/resources/views/partials/seo-private.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/fonts.php'; ?>
</head>
<body>
<div class="app-shell admin-shell">
  <header class="app-mobile-bar">
    <button type="button" class="nav-toggle" data-sidebar-toggle aria-expanded="false" aria-controls="app-sidebar">
      <span class="nav-toggle-bars" aria-hidden="true"><i></i><i></i><i></i></span>
      <span class="visually-hidden">Menu</span>
    </button>
    <a href="<?= e(url('/admin')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
    <span class="chip admin-badge">Admin</span>
  </header>
  <div class="sidebar-backdrop" data-sidebar-close hidden></div>
  <?php require BASE_PATH . '/resources/views/partials/admin-sidebar.php'; ?>
  <div class="app-main">
    <?= $content ?>
  </div>
</div>
<?php require BASE_PATH . '/resources/views/partials/flash.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/tracking.php'; ?>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
