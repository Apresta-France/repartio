<?php
$stats = $stats ?? [];
$plans = $plans ?? [];
$planCounts = $planCounts ?? [];
$activity = $activity ?? [];
?>
<header class="app-top">
  <div>
    <h1>Administration</h1>
    <span class="eyebrow">Vue d’ensemble · <?= e(date('d/m/Y')) ?></span>
  </div>
</header>
<section class="kpi-row">
  <?php
  $kpis = [
      ['Clients', (string) (int) ($stats['clients'] ?? 0), 'var(--navy)'],
      ['Cette semaine', (string) (int) ($stats['week'] ?? 0), 'var(--teal-ink)'],
      ['Circuits', (string) (int) ($stats['circuits'] ?? 0), 'var(--blue)'],
      ['Messages', (string) (int) ($stats['messages'] ?? 0), 'var(--orange-ink)'],
  ];
  foreach ($kpis as $k): ?>
    <div class="kpi"><span class="k"><?= e($k[0]) ?></span><span class="v" style="color:<?= $k[2] ?>"><?= e($k[1]) ?></span></div>
  <?php endforeach; ?>
</section>
<section class="admin-page">
  <div class="admin-split">
    <div>
      <div class="admin-section-head">
        <h2>Forfaits</h2>
        <a href="<?= e(url('/admin/forfaits')) ?>">Gérer →</a>
      </div>
      <div class="table">
        <div class="table-row table-admin-plans table-head">
          <span>Forfait</span><span>Clients</span><span>Prix</span>
        </div>
        <?php foreach ($plans as $plan): ?>
          <a class="table-row table-admin-plans" href="<?= e(url('/admin/forfaits/' . $plan['slug'])) ?>">
            <span>
              <strong><?= e($plan['label']) ?></strong>
              <?php if (!empty($plan['featured'])): ?><span class="chip">Mis en avant</span><?php endif; ?>
            </span>
            <span class="mono"><?= (int) ($planCounts[$plan['slug']] ?? 0) ?></span>
            <span class="mono"><?= e(\App\Models\Plan::priceMonthly($plan)) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <div>
      <div class="admin-section-head">
        <h2>Activité récente</h2>
        <a href="<?= e(url('/admin/clients')) ?>">Clients →</a>
      </div>
      <div class="table">
        <?php if (!$activity): ?>
          <div class="table-row">Aucun mouvement pour l’instant.</div>
        <?php endif; ?>
        <?php foreach ($activity as $a): ?>
          <div class="table-row table-admin-activity">
            <span class="mono"><?= e(time_ago($a['created_at'])) ?></span>
            <span>
              <?= e($a['message']) ?>
              <?php if (!empty($a['first_name'])): ?>
                <span class="admin-quiet"> · <?= e($a['first_name']) ?></span>
              <?php endif; ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="admin-quick">
    <a class="card card-pad" href="<?= e(url('/admin/clients/nouveau')) ?>">
      <span class="eyebrow">Clients</span>
      <strong>Créer un compte</strong>
      <p class="lede">Ouvrir un client, lui attribuer un forfait et un rôle.</p>
    </a>
    <a class="card card-pad" href="<?= e(url('/admin/environnement')) ?>">
      <span class="eyebrow">Réglages</span>
      <strong>Fichier environnement</strong>
      <p class="lede">URL, MySQL, courrier — écriture du .env.</p>
    </a>
    <a class="card card-pad" href="<?= e(url('/admin/messages')) ?>">
      <span class="eyebrow">Contact</span>
      <strong><?= (int) ($stats['messages'] ?? 0) ?> message<?= (int) ($stats['messages'] ?? 0) > 1 ? 's' : '' ?></strong>
      <p class="lede"><?= (int) ($stats['mails'] ?? 0) ?> e-mail<?= (int) ($stats['mails'] ?? 0) > 1 ? 's' : '' ?> journalisé<?= (int) ($stats['mails'] ?? 0) > 1 ? 's' : '' ?>.</p>
    </a>
  </div>
</section>
