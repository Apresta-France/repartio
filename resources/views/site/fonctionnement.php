<?php
$cursor = '<span class="how-cursor" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22"><path d="M5.2 2.8 19 13.6l-6.6.6-2.6 6.8Z" fill="var(--ink)" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/></svg></span>';
?>
<section class="section" style="padding-bottom:56px;">
  <span class="eyebrow eyebrow-live">Fonctionnement</span>
  <h1 class="page-title" style="font-size:48px;max-width:24ch;margin:12px 0;">Un circuit se lit comme un plan de plomberie</h1>
  <p class="lede">Vous ne remplissez pas des catégories, vous branchez des tuyaux. Chaque bloc reçoit un montant, en garde une part s’il veut, et fait sortir le reste. Quand tous les tuyaux sont raccordés, le compteur « non affecté » tombe à zéro.</p>
</section>

<section class="page-split how-step" style="border-bottom:1px solid var(--line);">
  <div class="how-copy">
    <div class="how-copy-head">
      <span class="mono how-num">01</span>
      <h2>Poser les entrées</h2>
    </div>
    <p class="lede">Un bloc par source d’argent. Vous indiquez un montant mensuel — net, brut provisionné, ou variable moyenné.</p>
    <div class="how-bullets">
      <div><span>—</span>Un revenu peut alimenter plusieurs comptes.</div>
      <div><span>—</span>Les revenus irréguliers se saisissent en moyenne lissée.</div>
      <div><span>—</span>Les provisions (URSSAF, impôt) se posent comme des dépenses dédiées.</div>
    </div>
  </div>
  <div class="how-panel" data-how-demo>
    <div class="dots"></div>
    <span class="eyebrow how-panel-label">Entrées du mois type</span>
    <div class="how-demo is-place" aria-hidden="true">
      <aside class="how-pal">
        <span class="eyebrow">Poser un bloc</span>
        <div class="how-pal-item is-hot"><i style="background:var(--teal)"></i>Revenu</div>
        <div class="how-pal-item"><i style="background:var(--navy)"></i>Compte</div>
        <div class="how-pal-item"><i style="background:var(--blue)"></i>Livret</div>
      </aside>
      <div class="how-place-col">
        <article class="how-node is-n1" style="color:var(--teal)">
          <i class="how-bar" style="background:var(--teal)"></i>
          <span class="how-kind">Revenu</span>
          <strong class="how-title">Salaire</strong>
          <div class="how-row"><span>Par mois</span><b>2 620 €</b></div>
          <i class="how-port how-port-out"></i>
        </article>
        <article class="how-node is-n2" style="color:var(--teal)">
          <i class="how-bar" style="background:var(--teal)"></i>
          <span class="how-kind">Revenu</span>
          <strong class="how-title">Auto-entreprise</strong>
          <div class="how-row"><span>Par mois</span><b>3 120 €</b></div>
          <i class="how-port how-port-out"></i>
        </article>
        <article class="how-node is-n3" style="color:var(--teal)">
          <i class="how-bar" style="background:var(--teal)"></i>
          <span class="how-kind">Revenu</span>
          <strong class="how-title">Loyers du local</strong>
          <div class="how-row"><span>Par mois</span><b>540 €</b></div>
          <i class="how-port how-port-out"></i>
        </article>
        <div class="how-chip how-chip-sum">
          <span class="is-a">entrées · 0 €</span>
          <span class="is-b">entrées · 2 620 €</span>
          <span class="is-c">entrées · 5 740 €</span>
          <span class="is-d">entrées · 6 280 €</span>
        </div>
      </div>
      <?= $cursor ?>
    </div>
    <p class="how-caption">Le total des entrées est ce que le circuit doit redistribuer, jusqu’au dernier euro.</p>
  </div>
</section>

<section class="page-split how-step" style="border-bottom:1px solid var(--line);">
  <div class="how-copy">
    <div class="how-copy-head">
      <span class="mono how-num is-orange">02</span>
      <h2>Câbler les flux</h2>
    </div>
    <p class="lede">Vous tirez un fil du point de sortie d’un bloc vers le point d’entrée d’un autre, puis vous choisissez ce qui circule : un montant fixe, un pourcentage, ou tout le reste.</p>
    <div class="how-bullets">
      <div><span>—</span>« Tout le reste » évite de recalculer à la main.</div>
      <div><span>—</span>Un répartiteur signale si les parts ne font pas 100 %.</div>
      <div><span>—</span>Chaque montant est écrit sur le tuyau.</div>
    </div>
  </div>
  <div class="how-panel" data-how-demo>
    <div class="dots"></div>
    <span class="eyebrow how-panel-label">Sortie du compte courant</span>
    <div class="how-chip how-chip-unassigned">
      <span class="is-a">non affecté · 6 280 €</span>
      <span class="is-b">non affecté · 4 000 €</span>
      <span class="is-c">non affecté · 1 240 €</span>
      <span class="is-d">non affecté · 0 €</span>
    </div>
    <div class="how-demo is-wire" aria-hidden="true">
      <article class="how-node how-wire-from" style="color:var(--navy)">
        <i class="how-bar" style="background:var(--navy)"></i>
        <span class="how-kind">Compte</span>
        <strong class="how-title">Compte courant</strong>
        <div class="how-row"><span>Reçoit</span><b>6 280 €</b></div>
        <div class="how-row"><span>Reste</span><b class="how-reste"><i class="is-a">6 280 €</i><i class="is-b">4 000 €</i><i class="is-c">1 240 €</i><i class="is-d">0 €</i></b></div>
        <i class="how-port how-port-out is-armed"></i>
      </article>
      <svg class="how-wires" viewBox="0 0 72 240" fill="none" preserveAspectRatio="none">
        <path class="how-wire is-w1" pathLength="1" d="M 2 120 C 28 120, 44 36, 70 36"/>
        <path class="how-wire is-w2" pathLength="1" d="M 2 120 C 28 120, 44 120, 70 120"/>
        <path class="how-wire is-w3" pathLength="1" d="M 2 120 C 28 120, 44 204, 70 204"/>
        <circle class="how-pellet is-p1" r="3.2" cx="0" cy="0"/>
        <circle class="how-pellet is-p2" r="3.2" cx="0" cy="0"/>
        <circle class="how-pellet is-p3" r="3.2" cx="0" cy="0"/>
      </svg>
      <div class="how-wire-to">
        <article class="how-node is-t1" style="color:var(--red)">
          <i class="how-bar" style="background:var(--red)"></i>
          <span class="how-kind">Dépense</span>
          <strong class="how-title">Joint Factures</strong>
          <div class="how-row"><span>Reçoit</span><b class="how-in is-i1"><i class="is-off">0 €</i><i class="is-on">2 280 €</i></b></div>
          <i class="how-port how-port-in is-target1"></i>
        </article>
        <article class="how-node is-t2" style="color:var(--red)">
          <i class="how-bar" style="background:var(--red)"></i>
          <span class="how-kind">Dépense</span>
          <strong class="how-title">Joint Quotidien</strong>
          <div class="how-row"><span>Reçoit</span><b class="how-in is-i2"><i class="is-off">0 €</i><i class="is-on">2 760 €</i></b></div>
          <i class="how-port how-port-in is-target2"></i>
        </article>
        <article class="how-node is-t3" style="color:var(--orange)">
          <i class="how-bar" style="background:var(--orange)"></i>
          <span class="how-kind">Répartiteur</span>
          <strong class="how-title">Épargne</strong>
          <div class="how-row"><span>Reçoit</span><b class="how-in is-i3"><i class="is-off">0 €</i><i class="is-on">1 240 €</i></b></div>
          <i class="how-port how-port-in is-target3"></i>
        </article>
      </div>
      <span class="how-pill is-p1">2 280 €</span>
      <span class="how-pill is-p2">2 760 €</span>
      <span class="how-pill is-p3">tout le reste</span>
      <?= $cursor ?>
    </div>
    <p class="how-caption">Tant que le compteur « non affecté » n’est pas à zéro, le mois type est incomplet.</p>
  </div>
</section>

<section class="page-split how-step" style="border-bottom:1px solid var(--line);">
  <div class="how-copy">
    <div class="how-copy-head">
      <span class="mono how-num is-navy">03</span>
      <h2>Dérouler le temps</h2>
    </div>
    <p class="lede">Le mois type est répété sur l’horizon choisi. Les livrets capitalisent à leur taux, saturent à leur plafond, et le surplus part vers la destination câblée.</p>
    <div class="how-bullets">
      <div><span>—</span>Horizons selon le plan : 24 mois, 60 mois ou 50 ans.</div>
      <div><span>—</span>Chaque livret affiche sa date de saturation.</div>
      <div><span>—</span>La projection se recalcule à chaque modification.</div>
    </div>
  </div>
  <div class="how-panel" data-how-demo>
    <div class="dots"></div>
    <span class="eyebrow how-panel-label">Projection à 60 mois</span>
    <div class="how-chip how-chip-saved">
      <span class="is-a">patrimoine · 0 €</span>
      <span class="is-b">à 12 mois · 10 320 €</span>
      <span class="is-c">à 38 mois · 32 680 €</span>
      <span class="is-d">dans 5 ans · 55 786 €</span>
    </div>
    <div class="how-demo is-time" aria-hidden="true">
      <div class="how-time-nodes">
        <article class="how-node how-livret" style="color:var(--blue)">
          <i class="how-bar" style="background:var(--blue)"></i>
          <span class="how-kind">Livret</span>
          <strong class="how-title">Livret A</strong>
          <div class="how-row"><span>Reçoit</span><b>400 €</b></div>
          <div class="how-row"><span>Capital</span><b class="how-proj is-a-proj"><i class="is-a">0 €</i><i class="is-b">4 800 €</i><i class="is-c">15 200 €</i><i class="is-d">22 950 €</i></b></div>
          <div class="how-fill"><i class="is-a-fill"></i></div>
        </article>
        <article class="how-node how-livret is-lep" style="color:var(--blue)">
          <i class="how-bar" style="background:var(--blue)"></i>
          <span class="how-kind">Livret</span>
          <strong class="how-title">LEP</strong>
          <div class="how-row"><span>Reçoit</span><b>300 €</b></div>
          <div class="how-row"><span>Capital</span><b class="how-proj is-lep-proj"><i class="is-a">0 €</i><i class="is-b">3 600 €</i><i class="is-c">10 000 €</i><i class="is-d">10 000 €</i></b></div>
          <div class="how-fill"><i class="is-lep-fill"></i></div>
          <span class="how-full">plein · 38 mois</span>
        </article>
      </div>
      <div class="how-timebar">
        <span class="how-timebar-k">mois</span>
        <div class="how-timebar-track">
          <i class="how-timebar-cursor"></i>
          <em class="how-timebar-mark">38</em>
        </div>
        <strong class="how-timebar-n"><i class="is-a">0</i><i class="is-b">12</i><i class="is-c">38</i><i class="is-d">60</i></strong>
      </div>
    </div>
    <p class="how-caption">860 € épargnés chaque mois, 55 786 € dans cinq ans — le LEP sature en 38 mois.</p>
  </div>
</section>

<section class="section" style="background:var(--paper);">
  <div class="section-head"><span class="eyebrow">Vocabulaire</span><h2>Les cinq types de blocs</h2></div>
  <div class="split cols-5">
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
