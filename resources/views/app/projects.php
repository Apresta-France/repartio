<?php
$statuses = [
    'actif' => 'Actif',
    'scenario' => 'Scénario',
    'archive' => 'Archivé',
];
?>
<header class="app-top">
  <div>
    <h1>Mes circuits</h1>
    <span class="eyebrow"><?= (int) $activeCount ?> actifs · plan <?= e(\App\Models\Plan::label($user)) ?> <?= (int) $activeCount ?>/<?= (int) $limit ?></span>
  </div>
  <form method="post" action="<?= e(url('/app/circuits/nouveau')) ?>" style="margin-left:auto;"><?= csrf_field() ?><button class="btn btn-orange" type="submit">Nouveau circuit</button></form>
</header>
<section class="project-list">
  <?php foreach ($projects as $p):
      $payload = json_decode((string) $p['payload'], true) ?: [];
      $thumb = \App\Models\Project::thumb($payload);
      $blocks = \App\Models\Project::blockCount($payload);
      $status = (string) $p['status'];
      $unassigned = (float) $p['unassigned'];
      $horizon = (int) $p['horizon'];
      $role = (string) ($p['access_role'] ?? 'proprietaire');
      $isOwner = $role === 'proprietaire';
      $canManage = $isOwner || $role === 'gestion';
      ?>
    <article class="card project-card<?= $status === 'archive' ? ' is-archive' : '' ?>">
      <a class="project-card-preview" href="<?= e(url('/app/circuits/' . $p['id'])) ?>">
        <?php $wires = $thumb['wires']; $dots = $thumb['dots']; require BASE_PATH . '/resources/views/partials/circuit-thumb.php'; ?>
        <?php if ($blocks === 0): ?>
          <span class="project-card-empty">Aucun bloc</span>
        <?php endif; ?>
      </a>
      <div class="project-card-body">
        <div class="project-card-head">
          <a class="project-card-title" href="<?= e(url('/app/circuits/' . $p['id'])) ?>"><?= e($p['name']) ?></a>
          <?php if (!$isOwner): ?>
            <span class="project-status is-shared"><?= e(\App\Models\Access::LABELS[$role] ?? $role) ?><?= !empty($p['owner_name']) ? ' · ' . e($p['owner_name']) : '' ?></span>
          <?php endif; ?>
          <span class="project-status is-<?= e($status) ?>"><?= e($statuses[$status] ?? $status) ?></span>
        </div>
        <div class="project-stats">
          <div>
            <span>Entrées / mois</span>
            <strong><?= e(money($p['monthly_in'])) ?></strong>
          </div>
          <div<?= $unassigned > 0.004 ? ' class="is-warn"' : '' ?>>
            <span>Non affecté</span>
            <strong><?= e(money($p['unassigned'])) ?></strong>
          </div>
          <div>
            <span>Dans <?= $horizon ?> mois</span>
            <strong><?= e(money($p['projection'])) ?></strong>
          </div>
        </div>
        <div class="project-card-foot">
          <span class="mono"><?= e(time_ago($p['updated_at'])) ?> · <?= $blocks ?> bloc<?= $blocks > 1 ? 's' : '' ?></span>
          <div class="project-actions">
            <?php if ($canManage && $status !== 'archive'): ?>
              <a href="<?= e(url('/app/circuits/' . $p['id'] . '/partage')) ?>">Partager</a>
              <form method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/dupliquer')) ?>"><?= csrf_field() ?><button type="submit">Dupliquer</button></form>
              <form method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/archiver')) ?>"><?= csrf_field() ?><button type="submit"><?= $status === 'archive' ? 'Réactiver' : 'Archiver' ?></button></form>
            <?php endif; ?>
            <?php if ($isOwner): ?>
              <form class="is-danger" method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/supprimer')) ?>" data-confirm-delete data-confirm-name="<?= e($p['name']) ?>"><?= csrf_field() ?><button type="submit">Supprimer</button></form>
            <?php else: ?>
              <form class="is-danger" method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/quitter')) ?>" onsubmit="return confirm('Retirer ce circuit partagé de votre compte ? L’emplacement sera libéré.');"><?= csrf_field() ?><button type="submit">Quitter</button></form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
  <form method="post" action="<?= e(url('/app/circuits/nouveau')) ?>" class="card project-card project-card-new">
    <?= csrf_field() ?>
    <button type="submit">
      <span class="project-card-plus">+</span>
      <strong>Nouveau circuit</strong>
      <span>Partir de zéro, ou d’un circuit type</span>
    </button>
  </form>
</section>
<?php
$nextPlan = \App\Models\Plan::nextLabel($user);
if ($nextPlan):
    $need = \App\Models\Plan::circuitLimit($user) + 1;
    $needLabel = $need === 2 ? 'd’un deuxième circuit' : 'de plus de ' . \App\Models\Plan::circuitLimit($user) . ' circuits';
?>
<section class="card project-upsell">
  <div>
    <strong>Besoin <?= e($needLabel) ?> ?</strong>
    <div>Le plan <?= e($nextPlan) ?> élargit le nombre de circuits, l’horizon et les invitations.</div>
  </div>
  <a class="btn btn-navy" href="<?= e(url('/app/forfait')) ?>">Voir les forfaits</a>
</section>
<?php endif; ?>
<div class="builder-modal" data-confirm-modal hidden>
  <div class="builder-modal-backdrop" data-confirm-dismiss></div>
  <div class="builder-modal-card confirm-modal-card" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">Suppression</div>
        <h2 id="confirm-title" data-confirm-title>Supprimer ce circuit ?</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-confirm-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint" data-confirm-text>Cette action est définitive. Le circuit et sa projection seront perdus.</p>
    <div class="confirm-actions">
      <button type="button" class="btn btn-ghost" data-confirm-dismiss>Annuler</button>
      <button type="button" class="btn btn-danger" data-confirm-ok>Supprimer</button>
    </div>
  </div>
</div>
