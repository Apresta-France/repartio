<?php
$C = [
    'revenu' => 'oklch(0.62 0.12 192)',
    'compte' => 'oklch(0.32 0.09 265)',
    'repartiteur' => 'oklch(0.68 0.18 38)',
    'livret' => 'oklch(0.48 0.11 240)',
    'depense' => 'oklch(0.55 0.16 25)',
];
$templates = [
    ['Couple, comptes séparés', 'Couple', '11 blocs', 'Deux salaires, un joint pour les factures, un joint pour le quotidien, épargne par personne.',
        ['M40 30 C80 30 80 68 120 68', 'M40 104 C80 104 80 72 120 72', 'M164 70 C200 70 200 34 240 34', 'M164 74 C200 74 200 104 240 104'],
        [[8, 22, 32, $C['revenu']], [8, 96, 32, $C['revenu']], [120, 60, 44, $C['compte']], [240, 26, 52, $C['depense']], [240, 96, 52, $C['livret']]]],
    ['Auto-entrepreneur', 'Indépendant', '9 blocs', 'Chiffre d’affaires, provision URSSAF, rémunération vers le compte perso, épargne de précaution.',
        ['M40 68 C80 68 80 30 120 30', 'M40 72 C80 72 80 104 120 104', 'M164 32 C200 32 210 68 240 68'],
        [[8, 60, 32, $C['revenu']], [120, 22, 44, $C['compte']], [120, 96, 44, $C['depense']], [240, 60, 52, $C['livret']]]],
    ['Épargne de précaution', 'Épargne', '6 blocs', 'Un seul revenu, une règle de trois mois de charges, saturation du LEP puis du Livret A.',
        ['M40 68 C80 68 80 42 120 42', 'M164 44 C200 44 200 24 240 24', 'M164 48 C200 48 200 96 240 96'],
        [[8, 60, 32, $C['revenu']], [120, 34, 44, $C['repartiteur']], [240, 16, 52, $C['livret']], [240, 88, 52, $C['livret']]]],
    ['Objectif apport', 'Épargne', '10 blocs', 'Tout ce qui n’est pas dépensé va vers l’apport ; repartio affiche la date d’atteinte du montant cible.',
        ['M40 42 C80 42 80 68 120 68', 'M40 104 C80 104 80 72 120 72', 'M164 70 C210 70 210 42 240 42'],
        [[8, 34, 32, $C['revenu']], [8, 96, 32, $C['revenu']], [120, 60, 44, $C['repartiteur']], [240, 34, 52, $C['livret']]]],
    ['Famille avec enfants', 'Famille', '13 blocs', 'Allocations, livrets des enfants, dépenses courantes indexées, deux répartiteurs.',
        ['M40 28 C80 28 80 64 120 64', 'M40 100 C80 100 80 68 120 68', 'M164 66 C200 66 200 28 240 28', 'M164 70 C200 70 200 100 240 100'],
        [[8, 20, 32, $C['revenu']], [8, 92, 32, $C['revenu']], [120, 56, 44, $C['repartiteur']], [240, 20, 52, $C['livret']], [240, 92, 52, $C['depense']]]],
    ['Locatif + salaire', 'Indépendant', '8 blocs', 'Loyers perçus, charges du local, fiscalité provisionnée, surplus vers épargne longue.',
        ['M40 68 C80 68 80 36 120 36', 'M164 38 C200 38 200 22 240 22', 'M164 42 C200 42 200 96 240 96'],
        [[8, 60, 32, $C['revenu']], [120, 28, 44, $C['compte']], [240, 14, 52, $C['depense']], [240, 88, 52, $C['livret']]]],
];
?>
<section class="section" style="padding-bottom:44px;">
  <span class="eyebrow eyebrow-live">Circuits types</span>
  <h1 class="page-title" style="max-width:26ch;margin:12px 0;">Neuf circuits déjà câblés, à reprendre tels quels</h1>
  <p class="lede">Chaque modèle est un circuit complet. Vous l’ouvrez, vous remplacez les chiffres par les vôtres, et la projection se recalcule.</p>
  <div class="chips" style="margin-top:18px;">
    <?php foreach (['Tout','Couple','Indépendant','Épargne','Famille'] as $f): ?>
      <button type="button" class="chip <?= $f === 'Tout' ? 'active' : '' ?>" data-filter="<?= e($f) ?>" data-group="templates"><?= e($f) ?></button>
    <?php endforeach; ?>
    <span class="mono" style="margin-left:auto;font-size:11.5px;color:var(--faint);" data-filter-count="templates">6 éléments</span>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="grid-3">
    <?php foreach ($templates as $t): ?>
      <form method="post" action="<?= e(url('/app/circuits')) ?>" class="card" data-filter-item="<?= e($t[1]) ?>" data-filter-group="templates" style="display:flex;flex-direction:column;">
        <?= csrf_field() ?>
        <input type="hidden" name="name" value="<?= e($t[0]) ?>">
        <input type="hidden" name="template" value="couple">
        <?php $wires = $t[4]; $dots = $t[5]; require BASE_PATH . '/resources/views/partials/circuit-thumb.php'; ?>
        <div style="padding:16px 18px 18px;display:flex;flex-direction:column;gap:8px;flex:1;">
          <div style="display:flex;gap:10px;"><strong><?= e($t[0]) ?></strong><span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= e($t[2]) ?></span></div>
          <p style="margin:0;font-size:13px;line-height:1.5;color:var(--muted);"><?= e($t[3]) ?></p>
          <button class="btn btn-ghost" style="margin-top:auto;" type="submit">Ouvrir dans le builder</button>
        </div>
      </form>
    <?php endforeach; ?>
  </div>
</section>
