<div class="builder" data-builder data-payload='<?= e(json_encode($payload, JSON_UNESCAPED_UNICODE)) ?>'>
  <aside class="builder-side">
    <div>
      <a href="<?= e(url('/app/circuits')) ?>" class="eyebrow">← Mes circuits</a>
      <div class="builder-project-name"><?= e($project['name']) ?></div>
      <div class="eyebrow">Ventilation des revenus</div>
    </div>
    <div>
      <div class="eyebrow" style="margin-bottom:8px;">Poser un bloc</div>
      <div class="palette-list">
        <?php foreach ([
          ['revenu', 'Revenu', 'R', 'oklch(0.48 0.10 152)'],
          ['compte', 'Compte', 'C', 'oklch(0.48 0.10 248)'],
          ['repartiteur', 'Répartiteur', 'P', 'oklch(0.48 0.12 300)'],
          ['livret', 'Livret', 'L', 'oklch(0.55 0.11 62)'],
          ['depense', 'Dépense', 'D', 'oklch(0.52 0.14 32)'],
        ] as $p): ?>
          <button type="button" class="palette-item" data-add="<?= e($p[0]) ?>">
            <span class="dot" style="background:<?= $p[3] ?>"></span>
            <span><?= e($p[1]) ?></span>
            <span class="mono palette-key"><?= e($p[2]) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
      <p class="builder-hint">Cliquez un type, ou glissez-le sur le canvas. Reliez le point droit d’un bloc au point gauche d’un autre.</p>
    </div>
    <div>
      <div class="eyebrow" style="padding-bottom:8px;">Chaque mois</div>
      <div class="kv">
        <div><span>Entrées</span><strong class="mono" data-stat="in">0 €</strong></div>
        <div><span>Dépenses</span><strong class="mono" data-stat="out">0 €</strong></div>
        <div><span>Épargné</span><strong class="mono" data-stat="saved">0 €</strong></div>
        <div><span>Non affecté</span><strong class="mono" data-stat="unassigned">0 €</strong></div>
      </div>
    </div>
    <div class="builder-proj">
      <div class="builder-proj-row">
        <span class="eyebrow" style="color:var(--teal-ink);" data-horizon-label>Dans 5 ans</span>
        <strong class="mono" data-stat="proj">0 €</strong>
      </div>
      <p class="builder-proj-hint" data-stat="proj-hint"></p>
    </div>
    <div data-warns></div>
  </aside>

  <main class="builder-main">
    <header class="app-top">
      <form method="post" action="<?= e(url('/app/circuits/' . $project['id'])) ?>" data-save-form class="builder-toolbar">
        <?= csrf_field() ?>
        <button type="button" class="btn btn-ghost builder-side-toggle" data-builder-side-toggle aria-expanded="false">Blocs &amp; chiffres</button>
        <input name="name" data-name value="<?= e($project['name']) ?>" class="builder-name-input">
        <label class="builder-horizon">
          <span>Projection</span>
          <input type="number" min="1" max="360" step="1" data-horizon value="<?= e((string) ($payload['horizon'] ?? 60)) ?>">
          <span>mois</span>
        </label>
        <input type="hidden" name="payload" data-payload-input>
        <button class="btn btn-ghost" type="button" data-clear>Vider</button>
        <button class="btn btn-orange" type="submit">Enregistrer</button>
        <button type="button" class="btn btn-ghost" data-share-open>Partager</button>
        <button type="button" class="btn btn-ghost builder-props-toggle" data-props-toggle aria-expanded="false">Propriétés</button>
      </form>
    </header>
    <div class="canvas-wrap" data-canvas>
      <div class="dots"></div>
      <div data-layer class="builder-layer">
        <svg data-edges width="6000" height="4200" class="builder-edges"></svg>
        <div data-labels class="builder-labels"></div>
      </div>
      <div class="canvas-empty" data-empty>
        <strong>Le plan est vide</strong>
        <span>Posez un bloc à gauche, ou glissez-le ici. Un modèle vous sera proposé pour préremplir taux et plafonds.</span>
      </div>
      <div class="canvas-zoom">
        <button type="button" class="btn btn-ghost" data-zoom-out>−</button>
        <span class="mono" data-zoom>85%</span>
        <button type="button" class="btn btn-ghost" data-zoom-in>+</button>
        <button type="button" class="btn btn-ghost" data-fit>Ajuster</button>
      </div>
    </div>
  </main>

  <aside class="builder-props" data-props>
    <div class="builder-props-empty" data-props-empty>
      <div class="eyebrow">Propriétés</div>
      <p>Sélectionnez un bloc pour modifier son nom, ses montants, son taux ou ses liens sortants.</p>
    </div>
    <div data-props-form hidden></div>
  </aside>
</div>

<div class="builder-modal" data-preset-modal hidden>
  <div class="builder-modal-backdrop" data-preset-dismiss></div>
  <div class="builder-modal-card" role="dialog" aria-modal="true" aria-labelledby="preset-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow" data-preset-kind>Nouveau bloc</div>
        <h2 id="preset-title">Préconfigurer</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-preset-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint" data-preset-intro>Choisissez un modèle, ou partez vierge.</p>
    <div class="preset-groups" data-preset-list></div>
  </div>
</div>

<div class="builder-modal" data-share-modal hidden>
  <div class="builder-modal-backdrop" data-share-dismiss></div>
  <div class="builder-modal-card share-modal-card" role="dialog" aria-modal="true" aria-labelledby="share-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">Lecture seule</div>
        <h2 id="share-title">Partager le circuit</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-share-dismiss aria-label="Fermer">×</button>
    </div>
    <?php
      $returnTo = 'builder';
      require BASE_PATH . '/resources/views/partials/share-form.php';
    ?>
  </div>
</div>
