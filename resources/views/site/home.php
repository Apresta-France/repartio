<?php
$C = [
    'revenu' => 'oklch(0.62 0.12 192)',
    'compte' => 'oklch(0.32 0.09 265)',
    'repartiteur' => 'oklch(0.68 0.18 38)',
    'livret' => 'oklch(0.48 0.11 240)',
    'depense' => 'oklch(0.55 0.16 25)',
];
?>
<section class="hero">
  <div class="hero-copy">
    <div class="eyebrow eyebrow-live"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--teal);margin-right:8px;vertical-align:middle;"></span>Répartiteur de revenus</div>
    <h1>Chaque euro a<br>une trajectoire.<br><em>Dessinez-la.</em></h1>
    <p class="lede">repartio est un canvas de nœuds pour votre argent. Posez vos revenus, vos comptes, vos livrets, vos dépenses&nbsp;; reliez-les&nbsp;; lisez ce qui reste chaque mois et où vous en serez dans cinq ans.</p>
    <div class="cta-row">
      <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>">Construire mon circuit</a>
      <a class="btn btn-ghost" href="<?= e(url('/circuit-rempli')) ?>">Voir un circuit rempli</a>
    </div>
    <div class="stat-grid">
      <?php $heroStats = \App\Content::showcase()['stats'] ?? []; ?>
      <div><strong><?= e(money($heroStats['monthly_in'] ?? 0)) ?></strong><span>entrées réparties / mois</span></div>
      <div><strong>60</strong><span>mois de projection</span></div>
      <div><strong><?= e(money($heroStats['unassigned'] ?? 0)) ?></strong><span>euro non affecté</span></div>
    </div>
  </div>
  <div class="hero-canvas" data-hero-demo>
    <div class="hero-demo" aria-hidden="true">
    <div class="dots"></div>
    <div class="hero-canvas-bar">
      <span class="chip">mon-circuit · 60 mois</span>
      <span class="chip hero-unassigned is-warn" data-hero-unassigned>non affecté · 3 280 €</span>
    </div>
    <span class="chip hero-saved is-empty" data-hero-saved>dans 5 ans · <b data-hero-saved-amount>0 €</b></span>
    <div class="hero-palette">
      <span class="eyebrow">Poser un bloc</span>
      <div class="hero-palette-list">
        <?php foreach ([
            ['revenu', 'Revenu', 'oklch(0.48 0.10 152)'],
            ['compte', 'Compte', 'oklch(0.48 0.10 248)'],
            ['repartiteur', 'Répartiteur', 'oklch(0.48 0.12 300)'],
            ['livret', 'Livret', 'oklch(0.55 0.11 62)'],
            ['depense', 'Dépense', 'oklch(0.52 0.14 32)'],
        ] as $p): ?>
          <div class="palette-item" data-hero-kind="<?= e($p[0]) ?>">
            <span class="dot" style="background:<?= e($p[2]) ?>"></span>
            <span class="palette-item-label"><?= e($p[1]) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="hero-stage">
    <div class="hero-scene">
      <svg class="hero-wires" width="920" height="720">
        <?php
        $wires = [
            ['j-c', 'salaire', 'compte', 'revenu', false, 'M 252 111 C 302 111, 270 171, 320 171'],
            ['ae-c', 'ae', 'compte', 'revenu', false, 'M 252 251 C 302 251, 270 171, 320 171'],
            ['lo-c', 'loyers', 'compte', 'revenu', false, 'M 252 391 C 302 391, 270 171, 320 171'],
            ['c-p', 'compte', 'prelev', 'compte', true, 'M 552 171 C 602 171, 580 91, 630 91'],
            ['c-r', 'compte', 'repart', 'compte', true, 'M 552 171 C 649 171, 223 371, 320 371'],
            ['r-a', 'repart', 'livreta', 'repartiteur', true, 'M 552 371 C 602 371, 580 231, 630 231'],
            ['r-d', 'repart', 'ldds', 'repartiteur', true, 'M 552 371 C 602 371, 580 401, 630 401'],
            ['r-e', 'repart', 'lep', 'repartiteur', true, 'M 552 371 C 602 371, 580 481, 630 481'],
        ];
        foreach ($wires as [$id, $from, $to, $kind, $pending, $d]): ?>
          <path class="hero-wire<?= $pending ? ' is-pending' : ' is-on' ?>" data-hero-wire="<?= e($id) ?>" data-from="<?= e($from) ?>" data-to="<?= e($to) ?>" pathLength="1" d="<?= e($d) ?>" fill="none" stroke="<?= e($C[$kind]) ?>" stroke-width="1.7" stroke-linecap="round"></path>
        <?php endforeach; ?>
        <path class="hero-drag" data-hero-drag pathLength="1" d="" fill="none" stroke="oklch(0.52 0.04 255)" stroke-width="1.7" stroke-dasharray="6 7" stroke-linecap="round"></path>
      </svg>
      <?php
      $nodes = [
          ['salaire', 20, 60, 'revenu', 'Revenu', 'Salaire', [['Par mois', '1 320 €']], false],
          ['ae', 20, 200, 'revenu', 'Revenu', 'Auto-entreprise', [['Par mois', '1 800 €']], false],
          ['loyers', 20, 340, 'revenu', 'Revenu', 'Loyers du local', [['Par mois', '540 €']], false],
          ['compte', 320, 120, 'compte', 'Compte', 'Compte courant', [['Reçoit', '3 280 €'], ['Reste', '3 280 €', 'compte-reste']], false],
          ['repart', 320, 320, 'repartiteur', 'Répartiteur', 'Répartiteur épargne', [['Reçoit', '0 €', 'repart-in'], ['Ventilé', '0 %', 'repart-pct']], true],
          ['prelev', 630, 40, 'depense', 'Dépense', 'Prélèvements', [['Reçoit', '0 €', 'prelev-in']], true],
          ['livreta', 630, 180, 'livret', 'Livret', 'Livret A', [['Reçoit', '0 €', 'livreta-in'], ['Dans 60 mois', '0 €', 'livreta-proj']], true],
          ['ldds', 630, 350, 'livret', 'Livret', 'LDDS', [['Reçoit', '0 €', 'ldds-in'], ['Dans 60 mois', '0 €', 'ldds-proj']], true],
          ['lep', 630, 430, 'livret', 'Livret', 'LEP', [['Reçoit', '0 €', 'lep-in'], ['Dans 60 mois', '0 €', 'lep-proj']], true],
      ];
      foreach ($nodes as [$id, $x, $y, $kind, $label, $title, $rows, $pending]):
          $color = $C[$kind];
      ?>
        <div class="hero-node<?= $pending ? ' is-pending' : '' ?>" data-hero-node="<?= e($id) ?>" data-x="<?= (int) $x ?>" data-y="<?= (int) $y ?>" style="left:<?= (int) $x ?>px;top:<?= (int) $y ?>px;color:<?= e($color) ?>">
          <div class="bar" style="background:<?= e($color) ?>"></div>
          <span class="kind" style="color:<?= e($color) ?>"><?= e($label) ?></span>
          <div class="title"><?= e($title) ?></div>
          <?php foreach ($rows as $row): ?>
            <div class="row"><span><?= e($row[0]) ?></span><b<?= isset($row[2]) ? ' data-hero-val="' . e($row[2]) . '"' : '' ?>><?= e($row[1]) ?></b></div>
          <?php endforeach; ?>
          <div style="height:11px;"></div>
          <i class="port port-in"></i>
          <i class="port port-out" style="background:<?= e($color) ?>"></i>
        </div>
      <?php endforeach; ?>
      <div class="hero-node hero-ghost" data-hero-ghost hidden></div>
      <?php
      $flows = [
          ['j-c', 286, 133, '1 320 €', false],
          ['ae-c', 286, 203, '1 420 €', false],
          ['lo-c', 286, 273, '540 €', false],
          ['c-p', 591, 122, '2 280 €', true],
          ['c-r', 591, 262, '1 000 €', true],
          ['r-a', 591, 292, '400 €', true],
          ['r-d', 591, 386, '300 €', true],
          ['r-e', 591, 426, '300 €', true],
      ];
      foreach ($flows as [$id, $x, $y, $label, $pending]): ?>
        <div class="hero-flow<?= $pending ? ' is-pending' : ' is-on' ?>" data-hero-flow="<?= e($id) ?>" style="left:<?= (int) $x ?>px;top:<?= (int) $y ?>px;"><?= e($label) ?></div>
      <?php endforeach; ?>
    </div>
    </div>
    <div class="hero-cursor" data-hero-cursor>
      <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
        <path d="M5.2 2.8 19 13.6l-6.6.6-2.6 6.8Z" fill="var(--ink)" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/>
      </svg>
    </div>
    <div class="hero-zoom-chip"><span data-hero-zoom>100 %</span></div>
    <div class="hero-fade"></div>
    </div>
    <button type="button" class="hero-play" data-demo-open aria-haspopup="dialog" aria-controls="demo-modal">
      <span class="hero-play-disc" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="28" height="28"><path d="M8.2 5.4v13.2L19.4 12Z" fill="currentColor"/></svg>
      </span>
      <span class="hero-play-label">Voir la démo</span>
    </button>
  </div>
</section>

<section class="chip-bar">
  <span class="eyebrow">Sait répartir</span>
  <div class="chips-marquee">
    <div class="chips-track">
      <?php
      $chips = [
        'Salaires', 'Auto-entreprise', 'Loyers perçus', 'Dividendes',
        'URSSAF', 'Impôts', 'Comptes joints', 'Compte courant',
        'Livret A', 'LDDS', 'LEP', 'PEL', 'Livrets enfants',
        'Assurance-vie', 'Prélèvements', 'Crédit immobilier',
        'Dépenses libres', 'Aides CAF', 'Parts en %', 'Plafonds',
      ];
      foreach ([false, true] as $dup): ?>
        <div class="chips"<?= $dup ? ' aria-hidden="true"' : '' ?>>
          <?php foreach ($chips as $c): ?>
            <span class="chip"><?= e($c) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
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
      ['01', 'Posez vos blocs', 'oklch(0.95 0.03 192)', 'oklch(0.45 0.11 195)', 'Un bloc par revenu, compte, livret ou poste de dépense. Rien à catégoriser, rien à importer.', [['Salaire','1 320 €'],['Auto-entreprise','1 800 €'],['Loyers du local','540 €']]],
      ['02', 'Reliez les flux', 'oklch(0.96 0.04 38)', 'oklch(0.56 0.17 38)', 'Tirez un fil d’un bloc à l’autre, en montant fixe ou en pourcentage. Le solde de chaque bloc s’ajuste en direct.', [['Compte → Joint','2 280 €'],['Compte → Épargne','1 000 €'],['Non affecté','0 €']]],
      ['03', 'Lisez la suite', 'oklch(0.94 0.02 265)', 'oklch(0.36 0.09 265)', 'repartio déroule votre mois type sur 60 mois, applique taux et plafonds, et vous dit quand chaque livret sature.', [['Épargné / mois','860 €'],['Dans 5 ans','55 786 €'],['LEP A plein en','38 mois']]],
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
    <?php foreach (($featured ?? []) as $key => $t): ?>
      <?php $cta = ''; require BASE_PATH . '/resources/views/partials/template-card.php'; ?>
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
      ['F04', 'Projection à l’horizon choisi', 'De 24 mois à 50 ans selon le plan : le mois type est déroulé et chaque bloc porte sa valeur de fin de période.'],
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
      ['Hébergement', 'Données chiffrées au repos, serveurs en Suisse chez Infomaniak.'],
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
    <?php foreach (\App\Content::homeFaq() as $q): ?>
      <div class="faq-item">
        <button type="button" data-faq><span style="flex:1;"><?= e($q['q']) ?></span><span class="sign">+</span></button>
        <p><?= e($q['a']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="ressources">
  <div class="section-head">
    <span class="eyebrow">06 · Guides</span>
    <h2>Comment gérer son argent</h2>
    <a href="<?= e(url('/ressources')) ?>" style="margin-left:auto;font-weight:600;font-size:13.5px;">Tous les guides →</a>
  </div>
  <div class="split cols-3">
    <?php foreach ([
      ['Budget', '9 min', 'Réussir à épargner sans attendre « s’il reste »', 'Un fil fixe vers le livret, dès le salaire, avant les sorties. Le simulateur montre ce qui reste pour le quotidien.', 'reussir-a-epargner'],
      ['Épargne', '8 min', 'C’est quoi un Livret A ?', '1,70 %, 22 950 €, disponible tout de suite. À quoi il sert, ce qu’il ne fait pas, et en combien de mois un versement le remplit.', 'cest-quoi-livret-a'],
      ['Foyer', '12 min', 'Un couple, 6 280 € par mois, zéro euro non affecté', 'Le circuit complet d’une famille de quatre. Baissez l’auto-entreprise : voyez ce qui s’arrête.', 'couple-12338'],
    ] as $p): ?>
      <a href="<?= e(url('/ressources/' . $p[4])) ?>" style="padding:24px 26px;color:inherit;display:flex;flex-direction:column;gap:10px;">
        <div class="eyebrow" style="display:flex;"><span style="color:var(--teal-live);"><?= e($p[0]) ?></span><span style="margin-left:auto;color:var(--faint);"><?= e($p[1]) ?></span></div>
        <strong style="font-size:17.5px;letter-spacing:-.024em;line-height:1.25;"><?= e($p[2]) ?></strong>
        <span style="font-size:13.5px;line-height:1.55;color:var(--muted);"><?= e($p[3]) ?></span>
        <span class="ressources-live" style="margin-top:auto;width:fit-content;">Interactif</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
