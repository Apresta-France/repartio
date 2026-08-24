<header class="app-top">
  <div>
    <h1>Bonjour <?= e($user['first_name']) ?></h1>
    <span class="eyebrow"><?= $current ? e($current['name']) . ' · modifié ' . time_ago($current['updated_at']) : 'Aucun circuit pour le moment' ?></span>
  </div>
  <div style="margin-left:auto;">
    <a class="btn btn-orange" href="<?= e($current ? url('/app/circuits/' . $current['id']) : url('/app/circuits/nouveau')) ?>">Ouvrir le builder</a>
  </div>
</header>
<section class="kpi-row">
  <?php
  $kpis = [
    ['Entrées / mois', $current ? money($current['monthly_in']) : '0 €', 'var(--teal-ink)'],
    ['Dépenses', $current ? money($current['monthly_out']) : '0 €', 'var(--red)'],
    ['Épargné', $current ? money($current['monthly_saved']) : '0 €', 'var(--navy)'],
    ['Dans ' . (int) ($current['horizon'] ?? 60) . ' mois', $current ? money($current['projection']) : '0 €', 'var(--blue)'],
  ];
  foreach ($kpis as $k): ?>
    <div class="kpi"><span class="k"><?= e($k[0]) ?></span><span class="v" style="color:<?= $k[2] ?>"><?= e($k[1]) ?></span></div>
  <?php endforeach; ?>
</section>
<section style="padding:24px 28px;">
  <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:14px;">
    <h2 style="margin:0;font-size:16.5px;">Activité récente</h2>
    <a href="<?= e(url('/app/circuits')) ?>" style="margin-left:auto;font-size:12.5px;font-weight:600;">Tous mes circuits →</a>
  </div>
  <div class="table">
    <?php if (!$activity): ?>
      <div class="table-row">Aucun mouvement pour l’instant. Créez votre premier circuit.</div>
    <?php endif; ?>
    <?php foreach ($activity as $a): ?>
      <div class="table-row" style="grid-template-columns:132px 1fr 190px;">
        <span class="mono" style="font-size:11.5px;color:var(--faint);"><?= e(time_ago($a['created_at'])) ?></span>
        <span><?= e($a['message']) ?></span>
        <span class="mono" style="text-align:right;font-size:11.5px;color:var(--faint);"><?= e($a['project_name'] ?? '') ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
