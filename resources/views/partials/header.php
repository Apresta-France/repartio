<?php
$nav = $nav ?? '';
$links = [
    ['fonctionnement', 'Fonctionnement', '/fonctionnement'],
    ['circuits', 'Circuits types', '/circuits-types'],
    ['capacites', 'Capacités', '/capacites'],
    ['tarifs', 'Tarifs', '/tarifs'],
    ['donnees', 'Vos données', '/vos-donnees'],
    ['ressources', 'Ressources', '/ressources'],
];
$user = \App\Core\Auth::user();
?>
<header class="site-header" data-site-header>
  <a href="<?= e(url('/')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
  <nav id="site-nav">
    <?php foreach ($links as [$id, $label, $href]): ?>
      <a href="<?= e(url($href)) ?>" class="<?= $nav === $id ? 'active' : '' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
    <div class="nav-mobile-auth">
      <?php if ($user): ?>
        <a class="btn btn-ghost" href="<?= e(url('/app')) ?>">Tableau de bord</a>
      <?php else: ?>
        <a class="btn btn-ghost" href="<?= e(url('/connexion')) ?>">Se connecter</a>
      <?php endif; ?>
    </div>
  </nav>
  <div class="header-actions">
    <span class="hint">Gratuit · 1 circuit</span>
    <?php if ($user): ?>
      <a class="btn-link" href="<?= e(url('/app')) ?>">Tableau de bord</a>
      <a class="btn btn-orange" href="<?= e(url('/app/circuits')) ?>">Mes circuits</a>
    <?php else: ?>
      <a class="btn-link" href="<?= e(url('/connexion')) ?>">Se connecter</a>
      <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>">Ouvrir le builder</a>
    <?php endif; ?>
  </div>
  <button type="button" class="nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="site-nav">
    <span class="nav-toggle-bars" aria-hidden="true"><i></i><i></i><i></i></span>
    <span class="visually-hidden">Menu</span>
  </button>
</header>
<div class="nav-backdrop" data-nav-close hidden></div>
