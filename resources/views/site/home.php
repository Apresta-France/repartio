<?php
$C = [
    'revenu' => 'oklch(0.62 0.12 192)',
    'compte' => 'oklch(0.32 0.09 265)',
    'repartiteur' => 'oklch(0.68 0.18 38)',
    'livret' => 'oklch(0.48 0.11 240)',
    'depense' => 'oklch(0.55 0.16 25)',
];
$thumbs = [
    ['Couple, comptes séparés', '11 blocs', 'Deux salaires, un joint pour les factures, un joint pour le quotidien, épargne par personne.',
        ['M40 30 C80 30 80 68 120 68', 'M40 104 C80 104 80 72 120 72', 'M164 70 C200 70 200 34 240 34', 'M164 74 C200 74 200 104 240 104'],
        [[8, 22, 32, $C['revenu']], [8, 96, 32, $C['revenu']], [120, 60, 44, $C['compte']], [240, 26, 52, $C['depense']], [240, 96, 52, $C['livret']]]],
    ['Auto-entrepreneur', '9 blocs', 'Chiffre d’affaires, provision URSSAF, rémunération vers le compte perso, épargne de précaution.',
        ['M40 68 C80 68 80 30 120 30', 'M40 72 C80 72 80 104 120 104', 'M164 32 C200 32 210 68 240 68'],
        [[8, 60, 32, $C['revenu']], [120, 22, 44, $C['compte']], [120, 96, 44, $C['depense']], [240, 60, 52, $C['livret']]]],
    ['Épargne de précaution', '6 blocs', 'Un seul revenu, une règle de trois mois de charges, saturation du LEP puis du Livret A.',
        ['M40 68 C80 68 80 42 120 42', 'M164 44 C200 44 200 24 240 24', 'M164 48 C200 48 200 96 240 96'],
        [[8, 60, 32, $C['revenu']], [120, 34, 44, $C['repartiteur']], [240, 16, 52, $C['livret']], [240, 88, 52, $C['livret']]]],
];
?>
<section class="hero">
  <div class="hero-copy">
    <div class="eyebrow eyebrow-live"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--teal);margin-right:8px;vertical-align:middle;"></span>Répartiteur de revenus</div>
    <h1>Chaque euro a<br>une trajectoire.<br><em>Dessinez-la.</em></h1>
    <p class="lede">repartio est un canvas de nœuds pour votre argent. Posez vos revenus, vos comptes, vos livrets, vos dépenses&nbsp;; reliez-les&nbsp;; lisez ce qui reste chaque mois et où vous en serez dans cinq ans.</p>
    <div class="cta-row">
      <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>">Construire mon circuit</a>
      <a class="btn btn-ghost" href="<?= e(url('/circuits-types')) ?>">Voir un circuit rempli</a>
    </div>
    <div class="stat-grid">
      <div><strong>12 338 €</strong><span>entrées réparties / mois</span></div>
      <div><strong>60</strong><span>mois de projection</span></div>
      <div><strong>0 €</strong><span>euro non affecté</span></div>
    </div>
  </div>
  <div class="hero-canvas" aria-hidden="true">
    <div class="dots"></div>
    <div class="hero-canvas-bar">
      <span class="chip">mon-circuit · 60 mois</span>
      <span class="chip" style="margin-left:auto;color:var(--teal-ink);background:oklch(0.96 0.03 195);border-color:oklch(0.88 0.05 195);">non affecté · 0 €</span>
    </div>
    <div class="hero-scene">
      <svg class="hero-wires" width="920" height="720">
        <path d="M 252 111 C 308 111, 264 171, 320 171" fill="none" stroke="<?= e($C['revenu']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 252 251 C 308 251, 264 171, 320 171" fill="none" stroke="<?= e($C['revenu']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 252 391 C 308 391, 264 171, 320 171" fill="none" stroke="<?= e($C['revenu']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 552 171 C 608 171, 574 91, 630 91" fill="none" stroke="<?= e($C['compte']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 552 171 C 608 171, 574 371, 630 371" fill="none" stroke="<?= e($C['compte']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 552 371 C 608 371, 574 231, 630 231" fill="none" stroke="<?= e($C['repartiteur']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 552 371 C 608 371, 574 401, 630 401" fill="none" stroke="<?= e($C['repartiteur']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <path d="M 552 371 C 608 371, 574 571, 630 571" fill="none" stroke="<?= e($C['repartiteur']) ?>" stroke-width="1.7" stroke-linecap="round"></path>
      </svg>
      <?php
      $nodes = [
          [20, 60, 'revenu', 'Revenu', 'Salaire Julien', [['Par mois', '1 500 €']]],
          [20, 200, 'revenu', 'Revenu', 'Auto-entreprise', [['Par mois', '5 000 €']]],
          [20, 340, 'revenu', 'Revenu', 'Loyers du local', [['Par mois', '2 000 €']]],
          [320, 120, 'compte', 'Compte', 'Compte courant', [['Reçoit', '7 160 €'], ['Reste', '0 €']]],
          [320, 320, 'repartiteur', 'Répartiteur', 'Répartiteur épargne', [['Reçoit', '3 665 €'], ['Ventilé', '100 %']]],
          [630, 40, 'depense', 'Dépense', 'Prélèvements', [['Reçoit', '3 254 €']]],
          [630, 180, 'livret', 'Livret', 'Livret A', [['Reçoit', '1 466 €'], ['Dans 60 mois', '22 950 €']]],
          [630, 350, 'livret', 'Livret', 'LDDS', [['Reçoit', '1 100 €'], ['Dans 60 mois', '12 000 €']]],
          [630, 520, 'livret', 'Livret', 'LEP', [['Reçoit', '1 100 €'], ['Dans 60 mois', '10 000 €']]],
      ];
      foreach ($nodes as [$x, $y, $kind, $label, $title, $rows]):
          $color = $C[$kind];
      ?>
        <div class="hero-node" style="left:<?= (int) $x ?>px;top:<?= (int) $y ?>px;color:<?= e($color) ?>">
          <div class="bar" style="background:<?= e($color) ?>"></div>
          <span class="kind" style="color:<?= e($color) ?>"><?= e($label) ?></span>
          <div class="title"><?= e($title) ?></div>
          <?php foreach ($rows as $row): ?>
            <div class="row"><span><?= e($row[0]) ?></span><b><?= e($row[1]) ?></b></div>
          <?php endforeach; ?>
          <div style="height:11px;"></div>
          <i class="port port-in"></i>
          <i class="port port-out" style="background:<?= e($color) ?>"></i>
        </div>
      <?php endforeach; ?>
      <?php foreach ([[386, 132, '1 500 €'], [386, 202, '3 660 €'], [386, 272, '2 000 €'], [591, 122, '3 254 €'], [591, 262, '3 665 €'], [591, 292, '1 466 €'], [591, 386, '1 100 €'], [591, 462, '1 100 €']] as $f): ?>
        <div class="hero-flow" style="left:<?= (int) $f[0] ?>px;top:<?= (int) $f[1] ?>px;"><?= e($f[2]) ?></div>
      <?php endforeach; ?>
    </div>
    <div class="hero-fade"></div>
  </div>
</section>

<section class="chip-bar">
  <span class="eyebrow">Sait répartir</span>
  <div class="chips">
    <?php foreach (['Salaires','Auto-entreprise','Loyers perçus','URSSAF','Comptes joints','Livret A','LDDS','LEP','Livrets enfants','Prélèvements','Dépenses libres','Parts en %'] as $c): ?>
      <span class="chip"><?= e($c) ?></span>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="fonctionnement">
  <div class="section-head">
    <span class="eyebrow">01 · Fonctionnement</span>
    <h2>Trois gestes, pas de tableur</h2>
  </div>
  <div class="split cols-3">
    <?php
    $steps = [
      ['01', 'Posez vos blocs', 'oklch(0.95 0.03 192)', 'oklch(0.45 0.11 195)', 'Un bloc par revenu, compte, livret ou poste de dépense. Rien à catégoriser, rien à importer.', [['Salaire Julien','1 500 €'],['Auto-entreprise','5 000 €'],['Loyers du local','2 000 €']]],
      ['02', 'Reliez les flux', 'oklch(0.96 0.04 38)', 'oklch(0.56 0.17 38)', 'Tirez un fil d’un bloc à l’autre, en montant fixe ou en pourcentage. Le solde de chaque bloc s’ajuste en direct.', [['Compte → Joint','3 254 €'],['Compte → Épargne','3 665 €'],['Non affecté','0 €']]],
      ['03', 'Lisez la suite', 'oklch(0.94 0.02 265)', 'oklch(0.36 0.09 265)', 'repartio déroule votre mois type sur 60 mois, applique taux et plafonds, et vous dit quand chaque livret sature.', [['Épargné / mois','5 234 €'],['Dans 5 ans','105 615 €'],['Livret A plein en','16 mois']]],
    ];
    foreach ($steps as $s): ?>
      <div style="padding:26px 26px 28px;">
        <div style="display:flex;align-items:center;gap:11px;margin-bottom:12px;">
          <span class="mono" style="width:26px;height:26px;display:grid;place-items:center;border-radius:8px;background:<?= e($s[2]) ?>;color:<?= e($s[3]) ?>;font-size:11px;font-weight:600;"><?= e($s[0]) ?></span>
          <strong style="font-size:17.5px;letter-spacing:-.022em;"><?= e($s[1]) ?></strong>
        </div>
        <p style="margin:0 0 14px;font-size:14px;line-height:1.55;color:var(--muted);"><?= e($s[4]) ?></p>
        <div class="kv">
          <?php foreach ($s[5] as $row): ?>
            <div><span class="k"><?= e($row[0]) ?></span><strong class="mono" style="margin-left:auto;"><?= e($row[1]) ?></strong></div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" style="background:var(--paper);">
  <div class="section-head">
    <span class="eyebrow">02 · Circuits types</span>
    <h2>Partez d’un circuit déjà câblé</h2>
    <a href="<?= e(url('/circuits-types')) ?>" style="margin-left:auto;font-weight:600;font-size:13.5px;">Tous les modèles →</a>
  </div>
  <p class="lede" style="margin-bottom:28px;">Chaque modèle est un vrai circuit ouvrable&nbsp;: remplacez les montants par les vôtres, la projection se recalcule.</p>
  <div class="grid-3">
    <?php foreach ($thumbs as $t): ?>
      <a class="card" href="<?= e(url('/circuits-types')) ?>" style="color:inherit;">
        <?php $wires = $t[3]; $dots = $t[4]; require BASE_PATH . '/resources/views/partials/circuit-thumb.php'; ?>
        <div style="padding:16px 17px 18px;">
          <div style="display:flex;gap:10px;align-items:baseline;"><strong style="letter-spacing:-.02em;"><?= e($t[0]) ?></strong><span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= e($t[1]) ?></span></div>
          <p style="margin:8px 0 0;font-size:13px;line-height:1.5;color:var(--muted);"><?= e($t[2]) ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="capacites">
  <div class="section-head"><span class="eyebrow">03 · Capacités</span><h2>Ce que le moteur sait faire</h2></div>
  <div class="split cols-2">
    <?php foreach ([
      ['F01', 'Répartiteurs en pourcentage', 'Un bloc qui découpe ce qu’il reçoit en parts, et qui vous prévient dès que la somme des parts ne fait pas 100 %.'],
      ['F02', 'Plafonds et taux réels', 'Livret A, LDDS, LEP, livrets jeunes : plafond et taux préremplis, capitalisation annuelle, date de saturation calculée.'],
      ['F03', 'Compteur de non-affecté', 'Le canvas affiche en permanence l’euro qui traîne. Un circuit valide est un circuit à zéro.'],
      ['F04', 'Projection à l’horizon choisi', '12, 60, 120 mois : le mois type est déroulé et chaque bloc porte sa valeur de fin de période.'],
      ['F05', 'Scénarios comparés', 'Dupliquez un circuit, changez un versement, et lisez l’écart de patrimoine entre les deux variantes.'],
      ['F06', 'Canvas navigable', 'Glissé-déposé des blocs, panoramique au fond, zoom au clavier. Un circuit à trente blocs reste lisible.'],
    ] as $f): ?>
      <div style="padding:24px 26px;display:flex;gap:16px;">
        <span class="mono" style="font-size:10.5px;color:oklch(0.7 0.06 195);padding-top:4px;"><?= e($f[0]) ?></span>
        <div><strong style="font-size:16.5px;letter-spacing:-.022em;"><?= e($f[1]) ?></strong><p style="margin:6px 0 0;font-size:13.5px;line-height:1.55;color:var(--muted);max-width:48ch;"><?= e($f[2]) ?></p></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="dark-band band-2">
  <div style="padding:64px 44px 64px 32px;display:flex;flex-direction:column;gap:20px;">
    <span class="eyebrow" style="color:oklch(0.75 0.12 192);">04 · Vos données</span>
    <h2 style="color:#fff;margin:0;font-size:clamp(26px,4vw,33px);letter-spacing:-.034em;line-height:1.1;">Aucune connexion bancaire.<br>Aucun agrégateur.</h2>
    <p class="lede">repartio ne lit pas vos comptes&nbsp;: vous saisissez les montants que vous voulez répartir. Rien à autoriser, rien à révoquer, aucun historique de transactions sur nos serveurs.</p>
    <a class="btn btn-orange" href="<?= e(url('/vos-donnees')) ?>" style="align-self:flex-start;">Lire notre politique de données</a>
  </div>
  <div style="padding:48px 32px 48px 44px;background:var(--navy-soft);">
    <?php foreach ([
      ['Saisie manuelle', 'Aucun accès à vos comptes, aucun mandat DSP2, aucun agrégateur tiers.'],
      ['Hébergement', 'Données chiffrées au repos, serveurs en Union européenne.'],
      ['Export', 'Vos circuits s’exportent en JSON et CSV à tout moment, sans demande.'],
      ['Suppression', 'Un clic supprime le compte et les circuits associés, sans période de rétention.'],
    ] as $t): ?>
      <div class="trust-row" style="border-bottom-color:oklch(0.28 0.06 265);">
        <span class="eyebrow k" style="color:oklch(0.72 0.11 192);"><?= e($t[0]) ?></span>
        <span><?= e($t[1]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" style="background:var(--paper);">
  <div class="section-head"><span class="eyebrow">05 · Questions</span><h2>Ce qu’on nous demande</h2></div>
  <div class="grid-2" style="gap:0 46px;max-width:1200px;">
    <?php foreach ([
      ['Faut-il connecter ma banque ?', 'Non. repartio fonctionne uniquement avec les montants que vous saisissez : un salaire, un loyer, un versement mensuel. C’est ce qui permet de modéliser des situations qui n’existent pas encore.'],
      ['Est-ce un outil de budget ou de projection ?', 'Les deux, dans le même canvas : le circuit décrit votre mois type, la projection déroule ce mois type sur l’horizon que vous choisissez, plafonds de livrets inclus.'],
      ['Comment sont gérés les plafonds réglementaires ?', 'Chaque livret porte son plafond et son taux. Quand il sature, repartio vous dit en combien de mois et redirige le surplus vers la destination que vous avez câblée.'],
      ['Peut-on modéliser un couple avec des comptes séparés ?', 'Oui — c’est même le cas le plus courant : deux colonnes de comptes personnels, un ou plusieurs comptes joints, et des répartiteurs distincts par personne.'],
      ['Que se passe-t-il si un euro n’est pas affecté ?', 'Le compteur « non affecté » reste visible en permanence. Tant qu’il n’est pas à zéro, vous savez que le circuit est incomplet.'],
      ['Que contient la version gratuite ?', 'Trois circuits, tous les types de blocs et la projection jusqu’à 60 mois. Le payant ajoute les scénarios comparés, l’export et l’historique des versions.'],
    ] as $q): ?>
      <div class="faq-item">
        <button type="button" data-faq><span style="flex:1;"><?= e($q[0]) ?></span><span class="sign">+</span></button>
        <p><?= e($q[1]) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="ressources">
  <div class="section-head">
    <span class="eyebrow">06 · Ressources</span>
    <h2>Notes de terrain</h2>
    <a href="<?= e(url('/ressources')) ?>" style="margin-left:auto;font-weight:600;font-size:13.5px;">Tous les articles →</a>
  </div>
  <div class="split cols-3">
    <?php foreach ([
      ['Méthode', '6 min', 'Pourquoi votre budget ne tient pas dans un tableur', 'Un tableur décrit des totaux ; un circuit décrit des chemins. La différence se voit au troisième compte joint.', 'budget-tableur'],
      ['Réglementaire', '4 min', 'Ordre de remplissage des livrets réglementés', 'LEP, LDDS, Livret A : dans quel ordre saturer quand on épargne 1 500 € par mois.', 'ordre-livrets'],
      ['Étude de cas', '9 min', 'Un couple, 12 338 € par mois, zéro euro non affecté', 'Le circuit complet d’une famille de quatre, commenté bloc par bloc.', 'couple-12338'],
    ] as $p): ?>
      <a href="<?= e(url('/ressources/' . $p[4])) ?>" style="padding:24px 26px;color:inherit;display:flex;flex-direction:column;gap:10px;">
        <div class="eyebrow" style="display:flex;"><span style="color:var(--teal-live);"><?= e($p[0]) ?></span><span style="margin-left:auto;color:var(--faint);"><?= e($p[1]) ?></span></div>
        <strong style="font-size:17.5px;letter-spacing:-.024em;line-height:1.25;"><?= e($p[2]) ?></strong>
        <span style="font-size:13.5px;line-height:1.55;color:var(--muted);"><?= e($p[3]) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
