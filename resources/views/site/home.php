<section class="hero">
  <div class="hero-copy">
    <div class="eyebrow"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--teal);margin-right:8px;"></span>Répartiteur de revenus</div>
    <h1>Chaque euro a<br>une trajectoire.<br><em>Dessinez-la.</em></h1>
    <p class="lede">repartio est un canvas de nœuds pour votre argent. Posez vos revenus, vos comptes, vos livrets, vos dépenses ; reliez-les ; lisez ce qui reste chaque mois et où vous en serez dans cinq ans.</p>
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
  <div class="hero-canvas">
    <div class="dots"></div>
    <div style="position:absolute;top:14px;left:16px;right:16px;display:flex;gap:8px;z-index:3;">
      <span class="chip">mon-circuit · 60 mois</span>
      <span class="chip" style="margin-left:auto;color:var(--teal-ink);background:oklch(0.96 0.03 195);border-color:oklch(0.88 0.05 195);">non affecté · 0 €</span>
    </div>
    <svg viewBox="0 0 900 560" width="100%" height="100%" style="position:relative;z-index:1;padding:70px 20px 40px;">
      <path d="M220 120 C280 120 280 180 340 180" fill="none" stroke="var(--teal)" stroke-width="1.7"/>
      <path d="M220 280 C280 280 280 190 340 190" fill="none" stroke="var(--teal)" stroke-width="1.7"/>
      <path d="M520 185 C580 185 580 90 640 90" fill="none" stroke="var(--navy)" stroke-width="1.7"/>
      <path d="M520 200 C580 200 580 280 640 280" fill="none" stroke="var(--orange)" stroke-width="1.7"/>
      <rect x="40" y="90" width="180" height="70" rx="12" fill="#fff" stroke="var(--line)"/>
      <rect x="40" y="250" width="180" height="70" rx="12" fill="#fff" stroke="var(--line)"/>
      <rect x="340" y="150" width="180" height="80" rx="12" fill="#fff" stroke="var(--line)"/>
      <rect x="640" y="55" width="180" height="70" rx="12" fill="#fff" stroke="var(--line)"/>
      <rect x="640" y="245" width="180" height="70" rx="12" fill="#fff" stroke="var(--line)"/>
    </svg>
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
  <div class="split" style="grid-template-columns:repeat(3,1fr);">
    <?php
    $steps = [
      ['01', 'Posez vos blocs', 'Un bloc par revenu, compte, livret ou poste de dépense. Rien à catégoriser, rien à importer.', [['Salaire Julien','1 500 €'],['Auto-entreprise','5 000 €'],['Loyers du local','2 000 €']]],
      ['02', 'Reliez les flux', 'Tirez un fil d’un bloc à l’autre, en montant fixe ou en pourcentage. Le solde de chaque bloc s’ajuste en direct.', [['Compte → Joint','3 254 €'],['Compte → Épargne','3 665 €'],['Non affecté','0 €']]],
      ['03', 'Lisez la suite', 'repartio déroule votre mois type sur 60 mois, applique taux et plafonds, et vous dit quand chaque livret sature.', [['Épargné / mois','5 234 €'],['Dans 5 ans','105 615 €'],['Livret A plein en','16 mois']]],
    ];
    foreach ($steps as $s): ?>
      <div style="padding:26px;">
        <div style="display:flex;align-items:center;gap:11px;margin-bottom:12px;">
          <span class="mono" style="width:26px;height:26px;display:grid;place-items:center;border-radius:8px;background:oklch(0.95 0.03 192);color:var(--teal-ink);font-size:11px;font-weight:600;"><?= e($s[0]) ?></span>
          <strong style="font-size:17.5px;"><?= e($s[1]) ?></strong>
        </div>
        <p style="margin:0 0 14px;font-size:14px;line-height:1.55;color:var(--muted);"><?= e($s[2]) ?></p>
        <div class="kv">
          <?php foreach ($s[3] as $row): ?>
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
    <a href="<?= e(url('/circuits-types')) ?>" style="margin-left:auto;font-weight:600;">Tous les modèles →</a>
  </div>
  <p class="lede" style="margin-bottom:28px;">Chaque modèle est un vrai circuit ouvrable : remplacez les montants par les vôtres, la projection se recalcule.</p>
  <div class="grid-3">
    <?php foreach ([
      ['Couple, comptes séparés', '11 blocs', 'Deux salaires, un joint pour les factures, un joint pour le quotidien, épargne par personne.'],
      ['Auto-entrepreneur', '9 blocs', 'Chiffre d’affaires, provision URSSAF, rémunération vers le compte perso, épargne de précaution.'],
      ['Épargne de précaution', '6 blocs', 'Un seul revenu, une règle de trois mois de charges, saturation du LEP puis du Livret A.'],
    ] as $t): ?>
      <a class="card" href="<?= e(url('/circuits-types')) ?>" style="color:inherit;">
        <div style="height:136px;background:var(--grid);border-bottom:1px solid var(--line);"></div>
        <div style="padding:16px 17px 18px;">
          <div style="display:flex;gap:10px;align-items:baseline;"><strong><?= e($t[0]) ?></strong><span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= e($t[1]) ?></span></div>
          <p style="margin:8px 0 0;font-size:13px;color:var(--muted);"><?= e($t[2]) ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="capacites">
  <div class="section-head"><span class="eyebrow">03 · Capacités</span><h2>Ce que le moteur sait faire</h2></div>
  <div class="split" style="grid-template-columns:1fr 1fr;">
    <?php foreach ([
      ['F01', 'Répartiteurs en pourcentage', 'Un bloc qui découpe ce qu’il reçoit en parts, et qui vous prévient dès que la somme des parts ne fait pas 100 %.'],
      ['F02', 'Plafonds et taux réels', 'Livret A, LDDS, LEP, livrets jeunes : plafond et taux préremplis, capitalisation annuelle, date de saturation calculée.'],
      ['F03', 'Compteur de non-affecté', 'Le canvas affiche en permanence l’euro qui traîne. Un circuit valide est un circuit à zéro.'],
      ['F04', 'Projection à l’horizon choisi', '12, 60, 120 mois : le mois type est déroulé et chaque bloc porte sa valeur de fin de période.'],
      ['F05', 'Scénarios comparés', 'Dupliquez un circuit, changez un versement, et lisez l’écart de patrimoine entre les deux variantes.'],
      ['F06', 'Canvas navigable', 'Glissé-déposé des blocs, panoramique au fond, zoom au clavier. Un circuit à trente blocs reste lisible.'],
    ] as $f): ?>
      <div style="padding:24px 26px;display:flex;gap:16px;">
        <span class="mono" style="font-size:10.5px;color:var(--teal);padding-top:4px;"><?= e($f[0]) ?></span>
        <div><strong style="font-size:16.5px;"><?= e($f[1]) ?></strong><p style="margin:6px 0 0;font-size:13.5px;color:var(--muted);max-width:48ch;"><?= e($f[2]) ?></p></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="dark-band" style="display:grid;grid-template-columns:1fr 1fr;">
  <div style="padding:64px 44px 64px 32px;">
    <span class="eyebrow" style="color:oklch(0.75 0.12 192);">04 · Vos données</span>
    <h2 style="color:#fff;margin:16px 0;">Aucune connexion bancaire.<br>Aucun agrégateur.</h2>
    <p>repartio ne lit pas vos comptes : vous saisissez les montants que vous voulez répartir. Rien à autoriser, rien à révoquer.</p>
    <a class="btn btn-orange" href="<?= e(url('/vos-donnees')) ?>" style="margin-top:16px;">Lire notre politique de données</a>
  </div>
  <div style="padding:48px 32px;background:var(--navy-soft);">
    <?php foreach ([
      ['Saisie manuelle', 'Aucun accès à vos comptes, aucun mandat DSP2, aucun agrégateur tiers.'],
      ['Hébergement', 'Données chiffrées au repos, serveurs en Union européenne.'],
      ['Export', 'Vos circuits s’exportent en JSON et CSV à tout moment, sans demande.'],
      ['Suppression', 'Un clic supprime le compte et les circuits associés, sans période de rétention.'],
    ] as $t): ?>
      <div style="display:flex;gap:16px;padding:18px 0;border-bottom:1px solid oklch(0.32 0.07 265);">
        <span class="eyebrow" style="width:118px;flex:none;color:oklch(0.72 0.11 192);"><?= e($t[0]) ?></span>
        <span><?= e($t[1]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" style="background:var(--paper);">
  <div class="section-head"><span class="eyebrow">05 · Questions</span><h2>Ce qu’on nous demande</h2></div>
  <div class="grid-2" style="gap:0 46px;max-width:1200px;">
    <?php foreach ([
      ['Faut-il connecter ma banque ?', 'Non. repartio fonctionne uniquement avec les montants que vous saisissez.'],
      ['Est-ce un outil de budget ou de projection ?', 'Les deux, dans le même canvas : le circuit décrit votre mois type, la projection le déroule.'],
      ['Comment sont gérés les plafonds réglementaires ?', 'Chaque livret porte son plafond et son taux. Quand il sature, le surplus est redirigé.'],
      ['Peut-on modéliser un couple avec des comptes séparés ?', 'Oui — c’est même le cas le plus courant.'],
      ['Que se passe-t-il si un euro n’est pas affecté ?', 'Le compteur « non affecté » reste visible. Tant qu’il n’est pas à zéro, le circuit est incomplet.'],
      ['Que contient la version gratuite ?', 'Trois circuits, tous les types de blocs et la projection jusqu’à 60 mois.'],
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
    <a href="<?= e(url('/ressources')) ?>" style="margin-left:auto;font-weight:600;">Tous les articles →</a>
  </div>
  <div class="split" style="grid-template-columns:repeat(3,1fr);">
    <?php foreach ([
      ['Méthode', '6 min', 'Pourquoi votre budget ne tient pas dans un tableur', 'Un tableur décrit des totaux ; un circuit décrit des chemins.', 'budget-tableur'],
      ['Réglementaire', '4 min', 'Ordre de remplissage des livrets réglementés', 'LEP, LDDS, Livret A : dans quel ordre saturer.', 'ordre-livrets'],
      ['Étude de cas', '9 min', 'Un couple, 12 338 € par mois, zéro euro non affecté', 'Le circuit complet d’une famille de quatre, commenté bloc par bloc.', 'couple-12338'],
    ] as $p): ?>
      <a href="<?= e(url('/ressources/' . $p[4])) ?>" style="padding:24px 26px;color:inherit;display:flex;flex-direction:column;gap:10px;">
        <div class="eyebrow" style="display:flex;"><span style="color:var(--teal-ink);"><?= e($p[0]) ?></span><span style="margin-left:auto;color:var(--faint);"><?= e($p[1]) ?></span></div>
        <strong style="font-size:17.5px;letter-spacing:-.024em;"><?= e($p[2]) ?></strong>
        <span style="font-size:13.5px;color:var(--muted);"><?= e($p[3]) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
