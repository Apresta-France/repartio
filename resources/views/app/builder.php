<?php
$canEdit = !empty($canEdit);
$canManage = !empty($canManage);
$readonly = !$canEdit;
?>
<div class="builder<?= $readonly ? ' is-readonly' : '' ?>" data-builder<?= $readonly ? ' data-readonly' : '' ?> data-payload='<?= e(json_encode($payload, JSON_UNESCAPED_UNICODE)) ?>'>
  <div class="builder-workspace">
  <aside class="builder-side">
    <a href="<?= e(url('/app/circuits')) ?>" class="btn btn-navy builder-back">← Mes circuits</a>
    <div>
      <div class="builder-side-label">
        <span class="eyebrow">Poser un bloc</span>
        <button type="button" class="builder-info-btn" data-info-toggle aria-expanded="false" aria-controls="info-blocks" aria-label="Aide : poser un bloc">
          <svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true">
            <circle cx="8" cy="8" r="6.25" stroke="currentColor" stroke-width="1.3"/>
            <circle cx="8" cy="5.2" r=".95" fill="currentColor"/>
            <path d="M8 7.35v4" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/>
          </svg>
        </button>
        <p class="builder-info-pop" id="info-blocks" hidden>Cliquez un type, ou glissez-le sur le canvas. Pour relier deux blocs, restez cliqué sur un point et glissez jusqu’au point opposé.</p>
      </div>
      <div class="palette-list">
        <?php foreach ([
          ['revenu', 'Revenu', 'R', 'oklch(0.48 0.10 152)', 'Fait entrer de l’argent. Une sortie, pas d’entrée.'],
          ['compte', 'Compte', 'C', 'oklch(0.48 0.10 248)', 'Reçoit, peut garder un matelas, et fait ressortir le reste.'],
          ['repartiteur', 'Répartiteur', 'P', 'oklch(0.48 0.12 300)', 'Découpe ce qu’il reçoit en parts. Ne conserve rien.'],
          ['livret', 'Livret', 'L', 'oklch(0.55 0.11 62)', 'Accumule, porte un taux et un plafond, dit quand il sature.'],
          ['depense', 'Dépense', 'D', 'oklch(0.52 0.14 32)', 'Sortie définitive du circuit : loyer, courses, cotisations…'],
        ] as $p): ?>
          <button type="button" class="palette-item" data-add="<?= e($p[0]) ?>" data-hint="<?= e($p[4]) ?>" aria-label="<?= e($p[1] . '. ' . $p[4]) ?>">
            <span class="dot" style="background:<?= $p[3] ?>"></span>
            <span class="palette-item-label"><?= e($p[1]) ?></span>
            <span class="mono palette-key"><?= e($p[2]) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="builder-side-label">
        <span class="eyebrow">Annoter</span>
        <button type="button" class="builder-info-btn" data-info-toggle aria-expanded="false" aria-controls="info-notes" aria-label="Aide : annoter">
          <svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true">
            <circle cx="8" cy="8" r="6.25" stroke="currentColor" stroke-width="1.3"/>
            <circle cx="8" cy="5.2" r=".95" fill="currentColor"/>
            <path d="M8 7.35v4" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/>
          </svg>
        </button>
        <p class="builder-info-pop" id="info-notes" hidden>Un groupe encadre plusieurs blocs (famille, épargne…). Une note pose un commentaire, hors calcul.</p>
      </div>
      <div class="palette-list">
        <?php foreach ([
          ['groupe', 'Groupe', 'G', 'oklch(0.55 0.04 255)', 'Encadre plusieurs blocs pour les lire ensemble. Hors calcul.'],
          ['note', 'Note', 'N', 'oklch(0.62 0.10 85)', 'Un commentaire posé sur le plan. Hors calcul.'],
        ] as $p): ?>
          <button type="button" class="palette-item" data-add="<?= e($p[0]) ?>" data-hint="<?= e($p[4]) ?>" aria-label="<?= e($p[1] . '. ' . $p[4]) ?>">
            <span class="dot" style="background:<?= $p[3] ?>"></span>
            <span class="palette-item-label"><?= e($p[1]) ?></span>
            <span class="mono palette-key"><?= e($p[2]) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
    <div>
      <div class="builder-month-head">
        <span class="eyebrow">Chaque mois</span>
        <button type="button" class="builder-report-cta" data-report-open aria-label="Voir le rapport de projection">Rapport</button>
      </div>
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
  <div class="palette-flyout" data-palette-flyout hidden>
    <strong data-palette-flyout-title></strong>
    <p data-palette-flyout-text></p>
  </div>

  <main class="builder-main">
    <header class="app-top">
      <form method="post" action="<?= e(url('/app/circuits/' . $project['id'])) ?>" data-save-form class="builder-toolbar">
        <?= csrf_field() ?>
        <button type="button" class="btn btn-ghost builder-side-toggle" data-builder-side-toggle aria-expanded="false">Blocs &amp; chiffres</button>
        <input name="name" data-name value="<?= e($project['name']) ?>" class="builder-name-input"<?= $readonly ? ' readonly' : '' ?>>
        <div class="builder-horizon">
          <span>Projection</span>
          <input type="number" min="1" max="360" step="1" data-horizon value="<?= e((string) ($payload['horizon'] ?? 60)) ?>"<?= $readonly ? ' readonly' : '' ?>>
          <div class="builder-horizon-unit" data-horizon-unit>
            <button type="button" class="builder-horizon-unit-btn" data-horizon-unit-toggle aria-haspopup="listbox" aria-expanded="false">
              <span data-horizon-unit-label>mois</span>
            </button>
            <div class="builder-horizon-unit-menu" data-horizon-unit-menu hidden role="listbox">
              <button type="button" role="option" aria-selected="true" data-horizon-unit-opt="mois">mois</button>
              <button type="button" role="option" aria-selected="false" data-horizon-unit-opt="ans">ans</button>
            </div>
          </div>
        </div>
        <input type="hidden" name="payload" data-payload-input>
        <?php if ($canEdit): ?>
        <button class="btn btn-ghost" type="button" data-scenario-open>Charger un scénario</button>
        <button class="btn btn-ghost btn-icon" type="button" data-clear title="Vider le canvas" aria-label="Vider le canvas">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
            <path d="M4.5 7h15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M9 7V5.2A1.2 1.2 0 0 1 10.2 4h3.6A1.2 1.2 0 0 1 15 5.2V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M6.4 7.2 7.3 19a1.8 1.8 0 0 0 1.8 1.6h5.8a1.8 1.8 0 0 0 1.8-1.6l.9-11.8" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M10 11.2v5.2M14 11.2v5.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </button>
        <button class="btn btn-orange builder-save" type="submit" data-save-btn>Enregistrer</button>
        <?php endif; ?>
        <?php if ($canManage): ?>
        <button type="button" class="btn btn-ghost btn-icon" data-share-open title="Partager" aria-label="Partager">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
            <circle cx="18" cy="5.5" r="2.6" stroke="currentColor" stroke-width="1.8"/>
            <circle cx="6" cy="12" r="2.6" stroke="currentColor" stroke-width="1.8"/>
            <circle cx="18" cy="18.5" r="2.6" stroke="currentColor" stroke-width="1.8"/>
            <path d="M8.4 10.7 15.5 7M8.4 13.3 15.5 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </button>
        <?php endif; ?>
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
        <span><?= $canEdit ? 'Posez un bloc à gauche, ou chargez un scénario déjà câblé. Les montants sont des exemples : vous les remplacez ensuite.' : 'Ce circuit n’a pas encore de blocs.' ?></span>
        <?php if ($canEdit): ?>
        <button type="button" class="btn btn-orange" data-scenario-open>Charger un scénario</button>
        <?php endif; ?>
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
      <div class="link-coach" data-link-coach hidden role="status" aria-live="polite">
        <div class="link-coach-head">
          <strong>Reliez les deux blocs</strong>
          <button type="button" class="link-coach-close" data-link-coach-dismiss aria-label="Fermer">×</button>
        </div>
        <div class="link-coach-scene" aria-hidden="true">
          <div class="link-coach-node is-from">
            <span class="link-coach-bar" style="background:oklch(0.48 0.10 152)"></span>
            <span class="link-coach-kind" style="color:oklch(0.48 0.10 152)">Revenu</span>
            <span class="link-coach-title">Salaire</span>
            <span class="link-coach-port is-out"></span>
          </div>
          <div class="link-coach-node is-to">
            <span class="link-coach-bar" style="background:oklch(0.48 0.10 248)"></span>
            <span class="link-coach-kind" style="color:oklch(0.48 0.10 248)">Compte</span>
            <span class="link-coach-title">Courant</span>
            <span class="link-coach-port is-in"></span>
          </div>
          <svg class="link-coach-wires" viewBox="0 0 280 96" fill="none">
            <path class="link-coach-ghost" d="M104 49 C138 49 142 49 176 49"/>
            <path class="link-coach-line" d="M104 49 C138 49 142 49 176 49"/>
          </svg>
          <span class="link-coach-pill">reste</span>
          <span class="link-coach-cursor">
            <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
              <path d="M5.2 2.8 19 13.6l-6.6.6-2.6 6.8Z" fill="var(--ink)" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
          </span>
        </div>
        <p>Restez cliqué sur un point, puis glissez jusqu’au point opposé.</p>
      </div>
    </div>
  </main>

  <aside class="builder-props" data-props>
    <div class="builder-props-empty" data-props-empty>
      <div class="eyebrow">Propriétés</div>
      <p>Sélectionnez un bloc ou un groupe pour modifier son nom, sa couleur, ses montants ou ses liens.</p>
    </div>
    <div data-props-form hidden></div>
  </aside>
  </div>
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

<div class="builder-modal" data-setup-modal<?= empty($setup) ? ' hidden' : '' ?>>
  <div class="builder-modal-backdrop" data-setup-dismiss></div>
  <div class="builder-modal-card setup-modal-card" role="dialog" aria-modal="true" aria-labelledby="setup-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">Nouveau circuit</div>
        <h2 id="setup-title">Nommer et projeter</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-setup-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint setup-intro">Deux réglages pour démarrer — déjà préremplis. Fermez cette fenêtre si les valeurs par défaut vous conviennent, vous pourrez les changer ensuite dans la barre du haut.</p>
    <form class="setup-form" data-setup-form>
      <label class="field">
        <span>Nom du circuit</span>
        <input name="setup_name" data-setup-name value="<?= e($project['name']) ?>" maxlength="180" autocomplete="off" placeholder="Nouveau circuit">
        <span class="field-hint">Pour le retrouver dans votre liste — «&nbsp;Budget foyer&nbsp;», «&nbsp;Objectif apport&nbsp;»…</span>
      </label>
      <div class="field">
        <span>Durée de la projection</span>
        <div class="setup-horizon">
          <input type="number" name="setup_horizon" data-setup-horizon min="1" max="360" step="1" value="<?= e((string) ($payload['horizon'] ?? 60)) ?>">
          <span>mois</span>
        </div>
        <div class="chips setup-horizon-chips">
          <button type="button" class="chip<?= (int) ($payload['horizon'] ?? 60) === 12 ? ' active' : '' ?>" data-setup-preset="12">12 mois · 1 an</button>
          <button type="button" class="chip<?= (int) ($payload['horizon'] ?? 60) === 60 ? ' active' : '' ?>" data-setup-preset="60">60 mois · 5 ans</button>
          <button type="button" class="chip<?= (int) ($payload['horizon'] ?? 60) === 120 ? ' active' : '' ?>" data-setup-preset="120">120 mois · 10 ans</button>
        </div>
        <span class="field-hint">Le mois type est répété jusqu’à cet horizon : soldes, intérêts et saturation des livrets. 60 mois est le défaut.</span>
      </div>
      <div class="setup-actions">
        <button class="btn btn-orange" type="submit">Commencer</button>
        <button class="btn btn-ghost" type="button" data-scenario-open>Charger un scénario</button>
        <button class="btn btn-ghost" type="button" data-setup-dismiss>Plus tard</button>
      </div>
    </form>
  </div>
</div>

<?php
$scenarios = \App\Content::templates();
$scenarioCategories = array_values(array_unique(array_map(static fn (array $t): string => $t['category'], $scenarios)));
$scenarioCount = count($scenarios);
$scenarioCatalog = [];
foreach ($scenarios as $key => $t) {
    $scenarioCatalog[$key] = [
        'title' => $t['title'],
        'category' => $t['category'],
        'payload' => $t['payload'],
    ];
}
?>
<div class="builder-modal" data-scenario-modal hidden>
  <div class="builder-modal-backdrop" data-scenario-dismiss></div>
  <div class="builder-modal-card scenario-modal-card" role="dialog" aria-modal="true" aria-labelledby="scenario-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">Circuits types</div>
        <h2 id="scenario-title">Charger un scénario</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-scenario-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint">Un circuit déjà câblé, à adapter. Filtrez par situation, puis chargez l’exemple : les chiffres se remplacent ensuite.</p>
    <div class="chips scenario-chips">
      <?php foreach (array_merge(['Tout'], $scenarioCategories) as $f): ?>
        <button type="button" class="chip <?= $f === 'Tout' ? 'active' : '' ?>" data-filter="<?= e($f) ?>" data-group="scenarios"><?= e($f) ?></button>
      <?php endforeach; ?>
      <span class="mono scenario-count" data-filter-count="scenarios"><?= $scenarioCount ?> élément<?= $scenarioCount > 1 ? 's' : '' ?></span>
    </div>
    <div class="scenario-grid">
      <?php foreach ($scenarios as $key => $t): ?>
        <button type="button" class="scenario-card" data-scenario-load="<?= e((string) $key) ?>" data-filter-item="<?= e($t['category']) ?>" data-filter-group="scenarios">
          <div class="scenario-card-top">
            <span class="chip"><?= e($t['category']) ?></span>
            <span class="mono"><?= (int) $t['blocks'] ?> blocs</span>
          </div>
          <strong><?= e($t['title']) ?></strong>
          <span><?= e($t['hint']) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script type="application/json" data-scenarios><?= json_encode($scenarioCatalog, JSON_UNESCAPED_UNICODE) ?></script>

<?php require BASE_PATH . '/resources/views/partials/builder-report-modal.php'; ?>

<?php if ($canManage): ?>
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
<?php endif; ?>
