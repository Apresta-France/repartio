<?php
$horizon = (int) ($payload['horizon'] ?? $share['horizon'] ?? 60);
?>
<div class="preview-bar">
  <a href="<?= e(url('/')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
  <div class="preview-bar-copy">
    <strong><?= e($share['title']) ?></strong>
    <span>Aperçu public · lecture seule<?= !empty($share['owner_name']) ? ' · partagé par ' . e($share['owner_name']) : '' ?></span>
  </div>
  <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>">Créer mon circuit</a>
</div>
<div class="builder is-readonly" data-builder data-readonly data-payload='<?= e(json_encode($payload, JSON_UNESCAPED_UNICODE)) ?>'>
  <div class="builder-workspace">
  <aside class="builder-side">
    <div>
      <span class="eyebrow">Circuit partagé</span>
      <div class="builder-project-name"><?= e($share['title']) ?></div>
      <div class="eyebrow">Ventilation des revenus</div>
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
      <div class="builder-toolbar">
        <button type="button" class="btn btn-ghost builder-side-toggle" data-builder-side-toggle aria-expanded="false">Chiffres</button>
        <div class="builder-name-input" style="border-style:dashed;"><?= e($share['title']) ?></div>
        <label class="builder-horizon">
          <span>Projection</span>
          <span class="mono"><?= (int) $horizon ?></span>
          <span>mois</span>
        </label>
      </div>
    </header>
    <div class="canvas-wrap" data-canvas>
      <div class="dots"></div>
      <div data-layer class="builder-layer">
        <svg data-edges width="6000" height="4200" class="builder-edges"></svg>
        <div data-labels class="builder-labels"></div>
      </div>
      <div class="canvas-empty" data-empty>
        <strong>Ce circuit est vide</strong>
        <span>Aucun bloc n’a encore été posé sur ce plan.</span>
      </div>
      <div class="canvas-zoom">
        <button type="button" class="btn btn-ghost" data-zoom-out>−</button>
        <span class="mono" data-zoom>85%</span>
        <button type="button" class="btn btn-ghost" data-zoom-in>+</button>
        <button type="button" class="btn btn-ghost" data-fit>Ajuster</button>
      </div>
    </div>
    <?php require BASE_PATH . '/resources/views/partials/builder-time.php'; ?>
  </main>
  </div>
</div>
