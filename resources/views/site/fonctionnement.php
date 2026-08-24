<section class="section" style="padding-bottom:56px;">
  <span class="eyebrow">Fonctionnement</span>
  <h1 class="page-title" style="font-size:48px;max-width:24ch;margin:12px 0;">Un circuit se lit comme un plan de plomberie</h1>
  <p class="lede">Vous ne remplissez pas des catégories, vous branchez des tuyaux. Chaque bloc reçoit un montant, en garde une part s’il veut, et fait sortir le reste. Quand tous les tuyaux sont raccordés, le compteur « non affecté » tombe à zéro.</p>
</section>
<?php
$steps = [
  ['01', 'Poser les entrées', 'Un bloc par source d’argent. Vous indiquez un montant mensuel — net, brut provisionné, ou variable moyenné.', ['Un revenu peut alimenter plusieurs comptes.', 'Les revenus irréguliers se saisissent en moyenne lissée.', 'Les provisions (URSSAF, impôt) se posent comme des dépenses dédiées.'], [['Salaire Julien','1 500 €'],['Auto-entreprise','5 000 €'],['Loyers du local','2 000 €'],['Total entrées','12 338 €']]],
  ['02', 'Câbler les flux', 'Vous tirez un fil du point de sortie d’un bloc vers le point d’entrée d’un autre, puis vous choisissez ce qui circule : un montant fixe, un pourcentage, ou tout le reste.', ['« Tout le reste » évite de recalculer à la main.', 'Un répartiteur signale si les parts ne font pas 100 %.', 'Chaque montant est écrit sur le tuyau.'], [['→ Joint Factures','3 254 €'],['→ Joint Quotidien','2 110 €'],['→ Répartiteur épargne','tout le reste'],['Non affecté','0 €']]],
  ['03', 'Dérouler le temps', 'Le mois type est répété sur l’horizon choisi. Les livrets capitalisent à leur taux, saturent à leur plafond, et le surplus part vers la destination câblée.', ['Horizons prêts : 12, 60 et 120 mois.', 'Chaque livret affiche sa date de saturation.', 'La projection se recalcule à chaque modification.'], [['Épargné par mois','5 234 €'],['Patrimoine dans 5 ans','105 615 €'],['Livret A Julien plein en','16 mois'],['Dépenses cumulées','426 240 €']]],
];
foreach ($steps as $s): ?>
<section style="display:grid;grid-template-columns:minmax(340px,.85fr) 1.15fr;border-bottom:1px solid var(--line);">
  <div style="padding:44px 44px 46px 32px;border-right:1px solid var(--line);">
    <div style="display:flex;align-items:center;gap:11px;margin-bottom:14px;">
      <span class="mono" style="width:28px;height:28px;display:grid;place-items:center;border-radius:9px;background:oklch(0.95 0.03 192);color:var(--teal-ink);"><?= e($s[0]) ?></span>
      <h2 style="margin:0;"><?= e($s[1]) ?></h2>
    </div>
    <p class="lede"><?= e($s[2]) ?></p>
    <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;">
      <?php foreach ($s[3] as $b): ?><div style="display:flex;gap:10px;font-size:13.5px;"><span style="color:var(--orange);font-weight:700;">—</span><?= e($b) ?></div><?php endforeach; ?>
    </div>
  </div>
  <div style="padding:38px 32px;background:var(--grid);">
    <div class="kv" style="max-width:520px;">
      <?php foreach ($s[4] as $r): ?><div><span><?= e($r[0]) ?></span><strong class="mono" style="margin-left:auto;"><?= e($r[1]) ?></strong></div><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<section class="section" style="background:var(--paper);">
  <div class="section-head"><span class="eyebrow">Vocabulaire</span><h2>Les cinq types de blocs</h2></div>
  <div class="split" style="grid-template-columns:repeat(5,1fr);">
    <?php foreach ([
      ['Revenu', 'var(--teal)', 'Fait entrer de l’argent. N’a pas d’entrée, seulement une sortie.', 'salaire · loyers'],
      ['Compte', 'var(--navy)', 'Reçoit, peut garder un matelas, et fait ressortir le reste.', 'courant · joint'],
      ['Répartiteur', 'var(--orange)', 'Découpe ce qu’il reçoit en parts. Ne conserve rien.', 'épargne 60/30/10'],
      ['Livret', 'var(--blue)', 'Accumule, porte un taux et un plafond, dit quand il sature.', 'Livret A · LEP'],
      ['Dépense', 'var(--red)', 'Sortie définitive du circuit, cumulée sur l’horizon.', 'prélèvements · URSSAF'],
    ] as $b): ?>
      <div>
        <div style="height:4px;background:<?= $b[1] ?>"></div>
        <div style="padding:20px;">
          <span class="eyebrow" style="color:<?= $b[1] ?>"><?= e($b[0]) ?></span>
          <strong style="display:block;margin:8px 0;"><?= e($b[0]) ?></strong>
          <p style="margin:0;font-size:13px;color:var(--muted);"><?= e($b[2]) ?></p>
          <span class="mono" style="display:block;margin-top:10px;font-size:11px;color:var(--faint);"><?= e($b[3]) ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
