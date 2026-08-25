<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Administration') ?> — repartio.fr</title>
  <link rel="icon" href="<?= e(asset('img/logo.png')) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
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
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
