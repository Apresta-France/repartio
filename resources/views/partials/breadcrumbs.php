<?php
$crumbs = $breadcrumbs ?? [];
if ($crumbs === []) {
    return;
}
?>
<nav class="crumbs" aria-label="Fil d’Ariane">
  <?php foreach ($crumbs as $i => $crumb): ?>
    <?php if ($i > 0): ?><span aria-hidden="true">/</span><?php endif; ?>
    <?php if ($i < count($crumbs) - 1): ?>
      <a href="<?= e(url($crumb['path'])) ?>"><?= e($crumb['name']) ?></a>
    <?php else: ?>
      <span><?= e($crumb['name']) ?></span>
    <?php endif; ?>
  <?php endforeach; ?>
</nav>
