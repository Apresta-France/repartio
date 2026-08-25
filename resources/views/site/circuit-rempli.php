<?php
$pack = $pack ?? [];
$stats = $pack['stats'] ?? [];
$key = (string) ($pack['key'] ?? 'couple-complet');
$blocks = (int) ($pack['blocks'] ?? 0);
$packTitle = (string) ($pack['title'] ?? 'Famille, revenus mixtes');
$user = \App\Core\Auth::user();
$claimLabel = $user ? 'Ouvrir dans mon builder' : 'Reprendre ce circuit';
$sumItems = static function (array $nodes, string $id): float {
    foreach ($nodes as $node) {
        if (($node['id'] ?? '') !== $id) {
            continue;
        }
        $sum = 0.0;
        foreach ($node['items'] ?? [] as $item) {
            $sum += max(0.0, (float) ($item['amount'] ?? 0));
        }
        return $sum;
    }
    return 0.0;
};
$nodes = $pack['payload']['nodes'] ?? [];
$urssaf = $sumItems($nodes, 'd-urssaf');
$factures = $sumItems($nodes, 'd-prelev');
$quotidien = $sumItems($nodes, 'd-quot');
$steps = [
    [
        'id' => 'vue',
        'n' => '01',
        'title' => 'Le plan entier',
        'kicker' => $blocks . ' blocs, un seul mois type',
        'text' => 'Chaque euro entre à gauche, circule, et arrive quelque part. Le compteur « non affecté » est à zéro : le mois est entièrement décrit.',
        'nodes' => [],
        'month' => null,
    ],
    [
        'id' => 'entrees',
        'n' => '02',
        'title' => 'Cinq sources, pas une',
        'kicker' => money($stats['monthly_in'] ?? 0) . ' d’entrées',
        'text' => 'Deux salaires, une auto-entreprise, un local loué, les allocations. Les montants variables sont des moyennes lissées — assez honnêtes pour décider, assez stables pour projeter.',
        'nodes' => ['r-ae', 'r-sal-m', 'r-loyer', 'r-sal-j', 'r-alloc'],
        'month' => null,
    ],
    [
        'id' => 'urssaf',
        'n' => '03',
        'title' => 'L’URSSAF, enfin visible',
        'kicker' => money($urssaf) . ' provisionnés / mois',
        'text' => 'Dans un tableur, la cotisation disparaît deux mois sur trois. Ici, un fil part chaque mois du compte pro vers un bloc dédié. Le revenu vraiment disponible est celui qui reste après.',
        'nodes' => ['r-ae', 'c-pro', 'd-urssaf', 'c-j'],
        'month' => null,
    ],
    [
        'id' => 'joints',
        'n' => '04',
        'title' => 'Deux joints, plus d’arbitrage',
        'kicker' => 'Factures ' . money($factures) . ' · Quotidien ' . money($quotidien),
        'text' => 'Le joint Factures ne reçoit que ce que les prélèvements consomment. Le joint Quotidien reçoit une enveloppe fixe. Tant que les deux tiennent, personne ne négocie le dimanche soir.',
        'nodes' => ['c-j', 'c-m', 'c-fact', 'c-quot', 'd-prelev', 'd-quot'],
        'month' => null,
    ],
    [
        'id' => 'epargne',
        'n' => '05',
        'title' => 'Chacun son répartiteur',
        'kicker' => money($stats['monthly_saved'] ?? 0) . ' mis de côté',
        'text' => 'Chacun garde son épargne. Le répartiteur découpe ce qu’il reçoit entre LEP, LDDS et Livret A — sans recalculer à chaque hausse.',
        'nodes' => ['p-j', 'p-m', 'l-lep-j', 'l-ldds-j', 'l-a-j', 'l-lep-m', 'l-ldds-m', 'l-a-m'],
        'month' => null,
    ],
    [
        'id' => 'enfants',
        'n' => '06',
        'title' => 'Les livrets des enfants',
        'kicker' => '40 € × 2, depuis les allocations',
        'text' => 'Un virement fixe par enfant, prélevé sur le compte B. Petit, régulier, plafonné. Le circuit le traite comme n’importe quel autre livret.',
        'nodes' => ['r-alloc', 'c-m', 'l-kid1', 'l-kid2'],
        'month' => null,
    ],
    [
        'id' => 'projection',
        'n' => '07',
        'title' => 'Ce que cinq ans font aux plafonds',
        'kicker' => money($stats['projection'] ?? 0) . ' à 60 mois',
        'text' => 'Tous les livrets réglementés saturent. Le moteur l’écrit mois par mois. C’est précisément pour ça qu’on projette : le mois type est propre, le temps révèle le bouchon.',
        'nodes' => ['l-lep-j', 'l-ldds-j', 'l-a-j', 'l-lep-m', 'l-ldds-m', 'l-a-m', 'l-kid1', 'l-kid2'],
        'month' => 60,
    ],
];
?>
<section class="showcase-intro">
  <div class="showcase-intro-copy">
    <span class="eyebrow eyebrow-live">Circuit rempli · étude de cas</span>
    <h1>Un foyer, <?= e(money($stats['monthly_in'] ?? 0)) ?>, <em>zéro euro perdu.</em></h1>
    <p class="lede">Un couple, deux enfants. Cinq revenus, deux comptes joints, l’URSSAF provisionnée, six livrets réglementés. Le circuit est prêt : vous pouvez le parcourir, lancer la démo, puis le reprendre avec vos chiffres.</p>
    <div class="cta-row">
      <button type="button" class="btn btn-orange" data-showcase-play data-rv="event" data-rv-name="showcase_demo" data-rv-props='{"source":"intro"}'>Lancer la démo</button>
      <form method="post" action="<?= e(url('/app/circuits')) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="template" value="<?= e($key) ?>">
        <input type="hidden" name="name" value="<?= e($packTitle) ?>">
        <button type="submit" class="btn btn-ghost" data-rv="event" data-rv-name="showcase_claim" data-rv-props='{"source":"intro"}'><?= e($claimLabel) ?></button>
      </form>
    </div>
  </div>
  <div class="showcase-intro-stats">
    <?php foreach ([
      [money($stats['monthly_in'] ?? 0), 'entrées / mois'],
      [money($stats['monthly_saved'] ?? 0), 'épargné / mois'],
      [money($stats['unassigned'] ?? 0), 'non affecté'],
      [money($stats['projection'] ?? 0), 'à 60 mois'],
      [(string) $blocks, 'blocs sur le canvas'],
      ['60', 'mois de projection'],
    ] as $row): ?>
      <div>
        <strong class="mono"><?= e($row[0]) ?></strong>
        <span><?= e($row[1]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="showcase-stage" data-showcase>
  <script type="application/json" data-showcase-steps><?= json_encode($steps, JSON_UNESCAPED_UNICODE) ?></script>
  <aside class="showcase-rail">
    <span class="eyebrow">Parcourir le circuit</span>
    <ol class="showcase-steps">
      <?php foreach ($steps as $i => $step): ?>
        <li>
          <button type="button" class="showcase-step<?= $i === 0 ? ' is-on' : '' ?>" data-showcase-step="<?= (int) $i ?>">
            <span class="mono"><?= e($step['n']) ?></span>
            <span><?= e($step['title']) ?></span>
          </button>
        </li>
      <?php endforeach; ?>
    </ol>
    <div class="showcase-rail-actions">
      <button type="button" class="btn btn-orange" data-showcase-play data-rv="event" data-rv-name="showcase_demo" data-rv-props='{"source":"rail"}'>Lancer la démo</button>
      <button type="button" class="btn btn-ghost" data-showcase-stop hidden>Arrêter</button>
    </div>
  </aside>

  <div class="showcase-board">
    <div class="showcase-toolbar">
      <span class="chip">foyer-mixte · 60 mois</span>
      <span class="chip is-ok">non affecté · <?= e(money($stats['unassigned'] ?? 0)) ?></span>
      <span class="chip">épargné · <b class="mono" data-stat="saved"><?= e(money($stats['monthly_saved'] ?? 0)) ?></b></span>
      <span class="chip" style="margin-left:auto;" data-horizon-label>Dans 5 ans</span>
      <strong class="mono" data-stat="proj"><?= e(money($stats['projection'] ?? 0)) ?></strong>
    </div>

    <div class="builder is-readonly is-showcase" data-builder data-readonly data-payload='<?= e(json_encode($pack['payload'] ?? [], JSON_UNESCAPED_UNICODE)) ?>'>
      <div class="builder-workspace">
        <main class="builder-main">
          <div class="canvas-wrap" data-canvas>
            <div class="dots"></div>
            <div data-layer class="builder-layer">
              <svg data-edges width="6000" height="4200" class="builder-edges"></svg>
              <div data-labels class="builder-labels"></div>
            </div>
            <div class="canvas-dock">
              <?php require BASE_PATH . '/resources/views/partials/builder-time.php'; ?>
              <div class="canvas-zoom">
                <button type="button" class="btn btn-ghost" data-zoom-out>−</button>
                <span class="mono" data-zoom>85%</span>
                <button type="button" class="btn btn-ghost" data-zoom-in>+</button>
                <button type="button" class="btn btn-ghost" data-fit>Ajuster</button>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <article class="showcase-note" data-showcase-note>
      <span class="eyebrow" data-showcase-kicker><?= e($steps[0]['kicker']) ?></span>
      <strong data-showcase-title><?= e($steps[0]['title']) ?></strong>
      <p data-showcase-text><?= e($steps[0]['text']) ?></p>
    </article>
  </div>
</section>

<section class="section showcase-lessons">
  <div class="section-head">
    <span class="eyebrow">Ce que le canvas rend inévitable</span>
    <h2>Trois choses qu’un tableur n’écrit pas</h2>
  </div>
  <div class="split cols-3">
    <?php foreach ([
      ['Le fil qu’on n’osait pas tracer', 'Poser l’URSSAF comme une dépense mensuelle a fait perdre ' . money($urssaf) . ' de « revenu disponible » — et gagné un chiffre enfin vrai.'],
      ['Deux enveloppes, zéro réunion', 'Séparer factures et quotidien supprime l’arbitrage. Chaque joint a un job. S’il déborde, on le voit avant la fin du mois.'],
      ['Le plafond arrive plus tôt', 'Le mois type est propre. La projection montre quand chaque livret sature. C’est là que le circuit demande une destination de surplus.'],
    ] as $card): ?>
      <div style="padding:26px 26px 28px;">
        <strong style="display:block;font-size:17.5px;letter-spacing:-.022em;margin-bottom:10px;"><?= e($card[0]) ?></strong>
        <p style="margin:0;font-size:14px;line-height:1.55;color:var(--muted);"><?= e($card[1]) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="dark-band band-2 showcase-claim">
  <div style="padding:64px 44px 64px 32px;display:flex;flex-direction:column;gap:20px;">
    <span class="eyebrow" style="color:oklch(0.75 0.12 192);">À vous</span>
    <h2 style="color:#fff;margin:0;font-size:clamp(26px,4vw,33px);letter-spacing:-.034em;line-height:1.1;">Remplacez leurs montants<br>par les vôtres.</h2>
    <p class="lede">Le modèle s’ouvre tel quel dans le builder. Changez un salaire, un loyer, un versement : la projection se recalcule. Gratuit jusqu’à trois circuits, sans carte.</p>
    <form method="post" action="<?= e(url('/app/circuits')) ?>" style="align-self:flex-start;">
      <?= csrf_field() ?>
      <input type="hidden" name="template" value="<?= e($key) ?>">
      <input type="hidden" name="name" value="<?= e($packTitle) ?>">
      <button type="submit" class="btn btn-orange" data-rv="event" data-rv-name="showcase_claim" data-rv-props='{"source":"band"}'><?= e($claimLabel) ?></button>
    </form>
  </div>
  <div style="padding:48px 32px 48px 44px;background:var(--navy-soft);">
    <?php foreach ([
      ['Compte en deux minutes', 'Un e-mail, un mot de passe. Pas de banque à connecter, pas de carte à sortir.'],
      ['Le circuit déjà câblé', 'Vous partez de celui-ci, ou d’un des ' . count(\App\Content::templates()) . ' autres modèles.'],
      ['Vos chiffres, votre horizon', '60 mois par défaut, plafonds réglementaires préremplis, export à tout moment.'],
      ['Rien n’est connecté', 'repartio ne lit pas vos comptes. Vous saisissez ce que vous voulez répartir.'],
    ] as $t): ?>
      <div class="trust-row" style="border-bottom-color:oklch(0.28 0.06 265);">
        <span class="eyebrow k" style="color:oklch(0.72 0.11 192);"><?= e($t[0]) ?></span>
        <span><?= e($t[1]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
