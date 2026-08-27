<?php
$canEdit = !empty($canEdit);
$canManage = !empty($canManage);
$readonly = !$canEdit;
$circuitPath = \App\Models\Project::path($project);
?>
<div class="builder<?= $readonly ? ' is-readonly' : '' ?>" data-builder<?= $readonly ? ' data-readonly' : '' ?> data-project-id="<?= e((string) $project['uid']) ?>" data-user-id="<?= (int) $user['id'] ?>" data-revision="<?= (int) ($revision ?? 1) ?>"<?= !empty($liveAhead) ? ' data-live-ahead' : '' ?> data-live-url="<?= e(url($circuitPath . '/live')) ?>" data-versions-url="<?= e(url($circuitPath . '/versions')) ?>" data-restore-url="<?= e(url($circuitPath . '/versions/restaurer')) ?>" data-horizon-max="<?= (int) ($horizonMax ?? 24) ?>" data-horizon-default="<?= (int) ($horizonDefault ?? 24) ?>" data-payload='<?= e(json_encode($payload, JSON_UNESCAPED_UNICODE)) ?>'>
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
        <p class="builder-info-pop" id="info-blocks" hidden>Cliquez un type, ou glissez-le sur le canvas. Pour relier deux blocs, restez cliqué sur un point et glissez jusqu’au point opposé. Glissez dans le vide pour sélectionner plusieurs blocs ; Espace + glisser déplace le canvas.</p>
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
      <form method="post" action="<?= e(url($circuitPath)) ?>" data-save-form class="builder-toolbar">
        <?= csrf_field() ?>
        <button type="button" class="btn btn-ghost builder-side-toggle" data-builder-side-toggle aria-expanded="false">Blocs &amp; chiffres</button>
        <input name="name" data-name value="<?= e($project['name']) ?>" class="builder-name-input"<?= $readonly ? ' readonly' : '' ?>>
        <div class="builder-horizon">
          <span>Projection</span>
          <input type="number" min="1" max="<?= (int) ($horizonMax ?? 24) ?>" step="1" data-horizon value="<?= e((string) ($payload['horizon'] ?? ($horizonDefault ?? 24))) ?>"<?= $readonly ? ' readonly' : '' ?>>
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
        <div class="collab-peers" data-collab-peers hidden></div>
        <button type="button" class="btn btn-ghost btn-icon" data-history-open title="Historique" aria-label="Historique des versions">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
            <circle cx="12" cy="13" r="7.2" stroke="currentColor" stroke-width="1.8"/>
            <path d="M12 9.6v3.6l2.3 1.4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9.2 5.2 12 3.6 14.8 5.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
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
      <div class="collab-cursors" data-collab-cursors></div>
      <div class="collab-toast" data-collab-toast hidden role="status" aria-live="polite"></div>
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
      <div class="link-coach" data-split-coach hidden role="status" aria-live="polite">
        <div class="link-coach-head">
          <strong>Réglez la répartition</strong>
          <button type="button" class="link-coach-close" data-split-coach-dismiss aria-label="Fermer">×</button>
        </div>
        <div class="split-coach-scene" aria-hidden="true">
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
          <svg class="link-coach-wires" viewBox="0 0 280 140" fill="none">
            <path class="split-coach-line" d="M104 38 C138 38 142 38 176 38"/>
          </svg>
          <span class="split-coach-pill">
            <span class="is-amt">2 200 €</span>
            <span class="is-reste">reste</span>
          </span>
          <div class="split-coach-panel">
            <span class="split-coach-panel-label">Liens sortants</span>
            <div class="split-coach-link">
              <span class="split-coach-link-to">→ Courant</span>
              <div class="split-coach-row">
                <span class="split-coach-mode">
                  <span class="is-reste">Le reste</span>
                  <span class="is-fixe">Montant fixe</span>
                </span>
                <span class="split-coach-value">2200</span>
              </div>
            </div>
          </div>
          <span class="split-coach-cursor">
            <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
              <path d="M5.2 2.8 19 13.6l-6.6.6-2.6 6.8Z" fill="var(--ink)" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
          </span>
        </div>
        <p>Cliquez le premier bloc pour choisir le montant à répartir.</p>
      </div>
      <div class="link-coach" data-items-coach hidden role="status" aria-live="polite">
        <div class="link-coach-head">
          <strong>Regroupez les postes</strong>
          <button type="button" class="link-coach-close" data-items-coach-dismiss aria-label="Fermer">×</button>
        </div>
        <div class="items-coach-scene" aria-hidden="true">
          <div class="items-coach-node">
            <span class="link-coach-bar" style="background:oklch(0.52 0.14 32)"></span>
            <span class="link-coach-kind" style="color:oklch(0.52 0.14 32)">Dépense</span>
            <span class="link-coach-title">Charges</span>
            <span class="link-coach-port is-in"></span>
          </div>
          <div class="items-coach-card">
            <div class="items-coach-card-head">
              <span>Postes du mois</span>
              <span class="items-coach-add">Ajouter</span>
            </div>
            <div class="items-coach-row">
              <span>Loyer</span>
              <span>850</span>
            </div>
            <div class="items-coach-row is-extra">
              <span>EDF</span>
              <span>90</span>
            </div>
            <div class="items-coach-total">
              <span class="is-one">850 € / mois</span>
              <span class="is-two">940 € / mois</span>
            </div>
          </div>
          <span class="items-coach-cursor">
            <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
              <path d="M5.2 2.8 19 13.6l-6.6.6-2.6 6.8Z" fill="var(--ink)" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/>
            </svg>
          </span>
        </div>
        <p>Ajoutez plusieurs dépenses dans le même bloc pour les regrouper.</p>
      </div>
    </div>
  </main>

  <aside class="builder-props" data-props>
    <div class="builder-props-empty" data-props-empty>
      <div class="eyebrow">Propriétés</div>
      <p>Sélectionnez un bloc, ou glissez dans le vide pour en prendre plusieurs. Vous pourrez alors les déplacer, les supprimer, ou retirer leurs liaisons.</p>
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

<div class="builder-modal" data-items-catalog-modal hidden>
  <div class="builder-modal-backdrop" data-items-catalog-dismiss></div>
  <div class="builder-modal-card items-catalog-card" role="dialog" aria-modal="true" aria-labelledby="items-catalog-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">Postes du mois</div>
        <h2 id="items-catalog-title">Ajouter plusieurs postes</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-items-catalog-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint">Cochez les dépenses habituelles. Les montants se saisissent ensuite.</p>
    <label class="field items-catalog-search">
      <span class="visually-hidden">Filtrer les postes</span>
      <input type="search" data-items-catalog-search placeholder="Filtrer… EDF, loyer, train" autocomplete="off">
    </label>
    <div class="items-catalog-list" data-items-catalog-list></div>
    <div class="items-catalog-foot">
      <span class="mono" data-items-catalog-count>0 sélectionné</span>
      <button type="button" class="btn btn-orange" data-items-catalog-apply disabled>Ajouter</button>
    </div>
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
    <p class="builder-hint setup-intro">Deux réglages déjà préremplis. Vous pourrez les changer ensuite dans la barre du haut.</p>
    <form class="setup-form" data-setup-form>
      <label class="field">
        <span>Nom du circuit</span>
        <input name="setup_name" data-setup-name value="<?= e($project['name']) ?>" maxlength="180" autocomplete="off" placeholder="Nouveau circuit">
        <span class="field-hint">Pour le retrouver dans votre liste — «&nbsp;Budget foyer&nbsp;», «&nbsp;Objectif apport&nbsp;»…</span>
      </label>
      <div class="field">
        <div class="setup-horizon-head">
          <span>Durée de la projection d'épargne</span>
          <div class="setup-horizon">
            <input type="number" name="setup_horizon" data-setup-horizon min="1" max="<?= (int) ($horizonMax ?? 24) ?>" step="1" value="<?= e((string) ($payload['horizon'] ?? ($horizonDefault ?? 24))) ?>" aria-label="Durée en mois">
            <span>mois</span>
          </div>
        </div>
        <div class="chips setup-horizon-chips" role="group" aria-label="Durées fréquentes">
          <?php foreach (($horizonPresets ?? []) as $preset): ?>
            <button type="button" class="chip<?= (int) ($payload['horizon'] ?? ($horizonDefault ?? 24)) === (int) $preset['months'] ? ' active' : '' ?>" data-setup-preset="<?= (int) $preset['months'] ?>"><strong><?= e($preset['title']) ?></strong><span><?= e($preset['hint']) ?></span></button>
          <?php endforeach; ?>
        </div>
        <?php
        $setupPlan = \App\Models\Plan::of($user ?? null);
        $setupIsFree = (float) ($setupPlan['price_monthly_ht'] ?? 0) <= 0 && (float) ($setupPlan['price_yearly_ht'] ?? 0) <= 0;
        if ($setupIsFree && \App\Models\Plan::nextSlug($setupPlan)):
        ?>
          <p class="field-hint setup-horizon-note">* Pour une projection plus longue, <a href="<?= e(url('/app/forfait')) ?>">passez sur une formule supérieure</a>.</p>
        <?php endif; ?>
      </div>
      <div class="setup-actions">
        <button class="btn btn-ghost setup-later" type="button" data-setup-dismiss>Plus tard</button>
        <button class="btn btn-ghost" type="button" data-scenario-open>Charger un scénario</button>
        <button class="btn btn-orange" type="submit">Commencer</button>
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
    <div class="scenario-toolbar">
      <label class="field scenario-search">
        <span class="visually-hidden">Rechercher un scénario</span>
        <input type="search" data-filter-search="scenarios" placeholder="Rechercher un scénario… naissance, LEP, crédit" autocomplete="off">
      </label>
      <div class="chips scenario-chips">
        <?php foreach (array_merge(['Tout'], $scenarioCategories) as $f): ?>
          <button type="button" class="chip <?= $f === 'Tout' ? 'active' : '' ?>" data-filter="<?= e($f) ?>" data-group="scenarios"><?= e($f) ?></button>
        <?php endforeach; ?>
        <span class="mono scenario-count" data-filter-count="scenarios"><?= $scenarioCount ?> élément<?= $scenarioCount > 1 ? 's' : '' ?></span>
      </div>
    </div>
    <div class="scenario-grid">
      <?php foreach ($scenarios as $key => $t): ?>
        <?php $searchText = trim($t['title'] . ' ' . $t['hint'] . ' ' . $t['category'] . ' ' . ($t['search'] ?? '')); ?>
        <button type="button" class="scenario-card" data-scenario-load="<?= e((string) $key) ?>" data-filter-item="<?= e($t['category']) ?>" data-filter-group="scenarios" data-filter-text="<?= e($searchText) ?>">
          <div class="scenario-card-top">
            <span class="chip"><?= e($t['category']) ?></span>
            <span class="mono"><?= (int) $t['blocks'] ?> blocs</span>
          </div>
          <strong><?= e($t['title']) ?></strong>
          <span><?= e($t['hint']) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
    <p class="scenario-empty" data-filter-empty="scenarios" hidden>Aucun scénario ne correspond à cette recherche.</p>
  </div>
</div>
<script type="application/json" data-scenarios><?= json_encode($scenarioCatalog, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_INVALID_UTF8_SUBSTITUTE) ?></script>

<?php require BASE_PATH . '/resources/views/partials/builder-report-modal.php'; ?>

<div class="builder-modal" data-history-modal hidden>
  <div class="builder-modal-backdrop" data-history-dismiss></div>
  <div class="builder-modal-card history-modal-card" role="dialog" aria-modal="true" aria-labelledby="history-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">50 dernières versions</div>
        <h2 id="history-title">Historique du circuit</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-history-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint">Chaque enregistrement conserve qui a modifié, et à quelle heure. Restaurer remplace le circuit actuel ; l’état d’aujourd’hui est d’abord conservé.</p>
    <div class="version-list" data-history-list>
      <p class="builder-hint">Chargement…</p>
    </div>
  </div>
</div>

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
