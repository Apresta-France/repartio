<?php
$plan = \App\Models\Plan::of($user);
$limit = (int) $plan['circuits'];
$used = (int) ($activeCount ?? 0);
$invited = (int) ($memberCount ?? 0);
$membersLimit = (int) ($memberLimit ?? $plan['members']);
$atCircuitLimit = $used >= $limit;
$atInviteLimit = $membersLimit <= 0 || $invited >= $membersLimit;
$nextPlan = \App\Models\Plan::nextLabel($user);
$guides = $guides ?? [];
$slotsLeft = max(0, $limit - $used);
$inviteSlots = max(0, $membersLimit - $invited);
?>
<header class="app-top">
  <div>
    <h1>Bonjour <?= e($user['first_name']) ?></h1>
    <span class="eyebrow"><?= $current ? e($current['name']) . ' · modifié ' . time_ago($current['updated_at']) : 'Aucun circuit pour le moment' ?></span>
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
<section class="app-page">
  <div class="dash-split">
    <div class="dash-col">
      <div class="dash-head">
        <h2>Activité récente</h2>
        <a href="<?= e(url('/app/circuits')) ?>">Tous mes circuits →</a>
      </div>
      <div class="table">
        <?php if (!$activity): ?>
          <div class="table-row">Aucun mouvement pour l’instant. Créez votre premier circuit.</div>
        <?php endif; ?>
        <?php foreach ($activity as $a): ?>
          <div class="table-row table-activity">
            <span class="mono" style="font-size:11.5px;color:var(--faint);"><?= e(time_ago($a['created_at'])) ?></span>
            <span><?= e($a['message']) ?></span>
            <span class="mono" style="text-align:right;font-size:11.5px;color:var(--faint);"><?= e($a['project_name'] ?? '') ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="dash-col">
      <div class="dash-head">
        <h2>Ensuite</h2>
      </div>
      <div class="dash-actions">
        <?php if ($atCircuitLimit): ?>
          <a class="card dash-card" href="<?= e(url(\App\Models\Project::planChangePath('circuits'))) ?>">
            <span class="eyebrow">Circuit</span>
            <strong>Plus de place sur <?= e($plan['label']) ?></strong>
            <p><?= $nextPlan
                ? 'Archivez un circuit, quittez un partage, ou passez en ' . e($nextPlan) . ' pour en ouvrir un autre.'
                : 'Archivez un circuit ou quittez un partage pour libérer un emplacement.' ?></p>
            <span class="btn btn-ghost">Gérer mon forfait</span>
          </a>
        <?php else: ?>
          <form class="card card-action dash-card" method="post" action="<?= e(url('/app/circuits/nouveau')) ?>">
            <?= csrf_field() ?>
            <button class="card-action-hit" type="submit">
              <span class="dash-card-inner">
                <span class="eyebrow">Circuit</span>
                <strong><?= $current ? 'Nouveau circuit' : 'Créer mon premier circuit' ?></strong>
                <p><?= $current
                    ? ($slotsLeft === 1
                        ? 'Encore un emplacement sur ' . e($plan['label']) . '.'
                        : 'Encore ' . (int) $slotsLeft . ' emplacements sur ' . e($plan['label']) . '.')
                    : 'Partir de zéro, puis relier revenus, comptes et livrets.' ?></p>
                <span class="btn btn-orange"><?= $current ? 'Créer' : 'Commencer' ?></span>
              </span>
            </button>
          </form>
        <?php endif; ?>

        <a class="card dash-card" href="<?= e(url('/app/forfait')) ?>">
          <span class="eyebrow">Forfait</span>
          <strong>Plan <?= e($plan['label']) ?></strong>
          <p><?= (int) $used ?> / <?= (int) $limit ?> circuit<?= $limit > 1 ? 's' : '' ?><?= $membersLimit <= 0
              ? ' · pas d’invitation.'
              : ' · ' . (int) $invited . ' / ' . (int) $membersLimit . ' invitation' . ($membersLimit > 1 ? 's' : '') . '.' ?></p>
          <span class="btn btn-ghost"><?= $nextPlan ? 'Voir ' . e($nextPlan) : 'Gérer mon forfait' ?></span>
        </a>

        <?php if ($atInviteLimit): ?>
          <a class="card dash-card" href="<?= e(url(\App\Models\Project::planChangePath('invitations'))) ?>">
            <span class="eyebrow">Accès</span>
            <strong><?= $membersLimit <= 0 ? 'Inviter n’est pas ouvert' : 'Plus d’invitation sur ' . e($plan['label']) ?></strong>
            <p><?= $membersLimit <= 0
                ? 'Le forfait Libre n’ouvre aucune invitation. Complet en permet une, Foyer jusqu’à dix.'
                : 'Retirez un accès, ou changez de forfait pour en inviter davantage.' ?></p>
            <span class="btn btn-ghost">Changer de forfait</span>
          </a>
        <?php else: ?>
          <a class="card dash-card" href="<?= e(url('/app/acces')) ?>">
            <span class="eyebrow">Accès</span>
            <strong>Inviter quelqu’un</strong>
            <p><?= $inviteSlots === 1
                ? 'Encore une invitation sur ' . e($plan['label']) . '. Chaque personne n’accède qu’aux circuits cochés.'
                : 'Encore ' . (int) $inviteSlots . ' invitations. Donnez un droit, circuit par circuit.' ?></p>
            <span class="btn btn-ghost">Ouvrir les accès</span>
          </a>
        <?php endif; ?>

        <div class="card dash-card dash-guides">
          <div class="dash-head">
            <span class="eyebrow">Guides</span>
            <a href="<?= e(url('/ressources')) ?>">Tous les articles →</a>
          </div>
          <strong>Lire un article récent</strong>
          <?php foreach ($guides as $g): ?>
            <a class="dash-guide" href="<?= e(url('/ressources/' . $g['slug'])) ?>">
              <span class="mono"><?= e($g['topic'] ?? $g['tag']) ?> · <?= e($g['read']) ?></span>
              <span><?= e($g['t']) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
