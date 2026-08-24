<section class="section" style="padding-bottom:44px;">
  <span class="eyebrow">Circuits types</span>
  <h1 class="page-title" style="font-size:48px;max-width:26ch;margin:12px 0;">Neuf circuits déjà câblés, à reprendre tels quels</h1>
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
    <?php foreach ([
      ['Couple, comptes séparés', 'Couple', '11 blocs', 'Deux salaires, un joint factures, un joint quotidien, épargne par personne.'],
      ['Auto-entrepreneur', 'Indépendant', '9 blocs', 'Chiffre d’affaires, provision URSSAF, rémunération, épargne de précaution.'],
      ['Épargne de précaution', 'Épargne', '6 blocs', 'Un revenu, trois mois de charges, saturation LEP puis Livret A.'],
      ['Objectif apport', 'Épargne', '10 blocs', 'Tout ce qui n’est pas dépensé va vers l’apport ; date d’atteinte affichée.'],
      ['Famille avec enfants', 'Famille', '13 blocs', 'Allocations, livrets des enfants, dépenses courantes, deux répartiteurs.'],
      ['Locatif + salaire', 'Indépendant', '8 blocs', 'Loyers perçus, charges du local, fiscalité provisionnée, surplus vers épargne.'],
    ] as $t): ?>
      <form method="post" action="<?= e(url('/app/circuits')) ?>" class="card" data-filter-item="<?= e($t[1]) ?>" data-filter-group="templates" style="display:flex;flex-direction:column;">
        <?= csrf_field() ?>
        <input type="hidden" name="name" value="<?= e($t[0]) ?>">
        <input type="hidden" name="template" value="couple">
        <div style="height:148px;background:var(--grid);border-bottom:1px solid var(--line);"></div>
        <div style="padding:16px 18px 18px;display:flex;flex-direction:column;gap:8px;flex:1;">
          <div style="display:flex;gap:10px;"><strong><?= e($t[0]) ?></strong><span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= e($t[2]) ?></span></div>
          <p style="margin:0;font-size:13px;color:var(--muted);"><?= e($t[3]) ?></p>
          <button class="btn btn-ghost" style="margin-top:auto;" type="submit">Ouvrir dans le builder</button>
        </div>
      </form>
    <?php endforeach; ?>
  </div>
</section>
