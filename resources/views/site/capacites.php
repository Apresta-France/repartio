<section class="section" style="padding-bottom:52px;">
  <span class="eyebrow">Capacités</span>
  <h1 class="page-title" style="font-size:48px;max-width:24ch;margin:12px 0;">Un moteur de flux, pas un agrégateur de dépenses</h1>
  <p class="lede">repartio ne cherche pas à classer votre passé. Il décrit une mécanique : qui reçoit quoi, dans quel ordre, jusqu’à quel plafond — et ce que cela donne dans cinq ans.</p>
</section>
<?php foreach ([
  ['C01 · Canvas', 'Construire sans se perdre', 'Un circuit réel dépasse vite les vingt blocs. Le canvas est fait pour rester lisible à cette échelle.', [
    ['Glissé-déposé libre', 'Chaque bloc se déplace à la souris ; les fils suivent.'],
    ['Panoramique et zoom', 'Fond glissable, zoom, bouton « ajuster » pour recadrer.'],
    ['Colonnes suggérées', 'Rangement par étage : revenus, comptes, répartiteurs, destinations.'],
    ['Survol traçant', 'Survoler un bloc met en évidence tous les fils qui y passent.'],
  ]],
  ['C02 · Moteur', 'Calculer juste, et le montrer', 'Les règles du moteur sont écrites noir sur blanc.', [
    ['Parts en pourcentage', 'Un répartiteur signale immédiatement si la somme ne fait pas 100 %.'],
    ['« Tout le reste »', 'Un fil peut emporter le solde d’un bloc.'],
    ['Plafonds et taux réels', 'Livret A, LDDS, LEP préremplis, date de saturation calculée.'],
    ['Débordement câblé', 'Quand un livret sature, le surplus part où vous l’avez dit.'],
  ]],
  ['C03 · Projection', 'Voir la suite, pas seulement le mois', 'Le mois type est déroulé sur l’horizon choisi.', [
    ['Horizons 12 / 60 / 120 mois', 'Changez l’horizon, toute la lecture se met à jour.'],
    ['Compteur de non-affecté', 'Un circuit valide est un circuit à zéro.'],
    ['Scénarios comparés', 'Dupliquez, changez un versement, lisez l’écart.'],
    ['Historique des versions', 'Chaque enregistrement est daté et restaurable.'],
  ]],
] as $g): ?>
<section style="border-bottom:1px solid var(--line);display:grid;grid-template-columns:minmax(300px,.72fr) 1.28fr;">
  <div style="padding:46px 44px 48px 32px;border-right:1px solid var(--line);">
    <span class="eyebrow"><?= e($g[0]) ?></span>
    <h2 style="margin:12px 0;"><?= e($g[1]) ?></h2>
    <p class="lede"><?= e($g[2]) ?></p>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;">
    <?php foreach ($g[3] as $i): ?>
      <div style="padding:26px 28px;border-bottom:1px solid var(--line-soft);border-right:1px solid var(--line-soft);">
        <strong><?= e($i[0]) ?></strong>
        <p style="margin:8px 0 0;font-size:13.5px;color:var(--muted);"><?= e($i[1]) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endforeach; ?>
<section class="dark-band section">
  <div class="section-head"><span class="eyebrow" style="color:oklch(0.75 0.12 192);">Limites assumées</span><h2 style="color:#fff;">Ce que repartio ne fait pas</h2></div>
  <div class="split" style="grid-template-columns:repeat(3,1fr);background:var(--navy-soft);border-color:var(--navy-soft);">
    <?php foreach ([
      ['Pas de synchronisation bancaire', 'Aucun accès à vos comptes, aucun mandat DSP2.'],
      ['Pas de conseil en placement', 'repartio calcule ce que vous décrivez. Il ne recommande aucun produit.'],
      ['Pas de marchés financiers', 'On reste sur des taux connus et des plafonds réglementaires.'],
    ] as $l): ?>
      <div style="background:var(--navy);padding:24px 26px;">
        <strong style="color:#fff;"><?= e($l[0]) ?></strong>
        <p style="margin:8px 0 0;"><?= e($l[1]) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>
