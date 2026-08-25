<?php
$plans = $plans ?? [];
$counts = $counts ?? [];
?>
<header class="app-top">
  <div>
    <h1>Forfaits</h1>
    <span class="eyebrow">Limites, prix et page /tarifs</span>
  </div>
  <a class="btn btn-orange" href="<?= e(url('/admin/forfaits/nouveau')) ?>">Nouveau forfait</a>
</header>
<section class="admin-page">
  <div class="table">
    <div class="table-row table-admin-planlist table-head">
      <span>Forfait</span><span>Circuits</span><span>Horizon</span><span>Invités</span><span>Mensuel</span><span>Clients</span>
    </div>
    <?php if (!$plans): ?>
      <div class="table-row">Aucun forfait. Les valeurs par défaut s’appliquent encore au site.</div>
    <?php endif; ?>
    <?php foreach ($plans as $plan): ?>
      <a class="table-row table-admin-planlist" href="<?= e(url('/admin/forfaits/' . $plan['slug'])) ?>">
        <span>
          <strong><?= e($plan['label']) ?></strong>
          <span class="mono admin-quiet"><?= e($plan['slug']) ?></span>
          <?php if (!empty($plan['featured'])): ?><span class="chip">Mis en avant</span><?php endif; ?>
        </span>
        <span class="mono"><?= (int) $plan['circuits'] ?></span>
        <span class="mono"><?= e(\App\Models\Plan::horizonLabel($plan)) ?></span>
        <span class="mono"><?= (int) $plan['members'] ?></span>
        <span class="mono"><?= e(\App\Models\Plan::priceMonthly($plan)) ?></span>
        <span class="mono"><?= (int) ($counts[$plan['slug']] ?? 0) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
