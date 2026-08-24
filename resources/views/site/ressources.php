<?php
$featuredSlug = \App\Articles::featuredSlug();
$featured = null;
$grid = [];
foreach ($posts as $p) {
    if ($p['slug'] === $featuredSlug) {
        $featured = $p;
        continue;
    }
    $grid[] = $p;
}
$guides = \App\Articles::guides();
?>
<section class="section ressources-intro">
  <span class="eyebrow eyebrow-live">Ressources</span>
  <h1 class="page-title">Notes de terrain sur la répartition des revenus</h1>
  <p class="lede">Des méthodes concrètes, des barèmes vérifiés, et des circuits commentés. Chaque fiche porte un simulateur : vous touchez les montants, le récit se recalcule. Pas de conseil en placement — de la mécanique.</p>
</section>

<section class="page-split ressources-lead">
  <?php if ($featured): ?>
    <div class="ressources-feature">
      <a class="ressources-feature-link" href="<?= e(url('/ressources/' . $featured['slug'])) ?>">
        <div class="eyebrow ressources-feature-meta">
          <span class="ressources-kicker">À la une</span>
          <span class="ressources-tag"><?= e($featured['tag']) ?></span>
          <span class="ressources-when"><?= e($featured['date']) ?> · <?= e($featured['read']) ?></span>
        </div>
        <strong class="ressources-feature-title"><?= e($featured['t']) ?></strong>
        <p class="lede"><?= e($featured['d']) ?></p>
        <?php if (!empty($featured['leadRows'])): ?>
          <div class="kv ressources-feature-rows">
            <?php foreach ($featured['leadRows'] as $row): ?>
              <div><span><?= e($row['k']) ?></span><strong class="mono"><?= e($row['v']) ?></strong></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <span class="ressources-more">Lire l’étude de cas →</span>
      </a>
      <div class="lab lab-teaser" data-lab="couple">
        <div class="lab-head">
          <span class="eyebrow">Essai immédiat</span>
          <strong>Baissez l’auto-entreprise</strong>
          <p>Le reste du foyer est figé. Seul le CA de l’auto-entreprise bouge.</p>
        </div>
        <div class="lab-field">
          <div class="lab-field-top"><span>CA auto-entreprise</span><b data-out="ae">1 800 €</b></div>
          <input type="range" min="0" max="3000" step="50" value="1800" data-in="ae">
        </div>
        <div class="lab-kpis lab-kpis-2">
          <div><span>Épargne A</span><strong data-out="save">460 €</strong></div>
          <div><span>Ce qui s’arrête</span><strong data-out="cut">Rien — circuit tenu</strong></div>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="ressources-guides">
    <span class="eyebrow">Guides de référence</span>
    <div class="ressources-guide-list">
      <?php foreach ($guides as $g): ?>
        <a href="<?= e(url('/ressources/' . $g['slug'])) ?>">
          <strong><?= e($g['t']) ?></strong>
          <span class="mono"><?= e($g['guideMeta'] ?? $g['read']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="ressources-guides-note">Chaque guide contient un simulateur : barèmes, parts d’un répartiteur, migration d’un tableur.</p>
  </div>
</section>

<section class="section">
  <div class="ressources-filter">
    <div class="chips">
      <?php foreach (['Tout', 'Méthode', 'Réglementaire', 'Étude de cas', 'Produit'] as $f): ?>
        <button type="button" class="chip <?= $f === 'Tout' ? 'active' : '' ?>" data-filter="<?= e($f) ?>" data-group="posts"><?= e($f) ?></button>
      <?php endforeach; ?>
    </div>
    <span class="mono ressources-count" data-filter-count="posts" data-filter-one="note" data-filter-many="notes"><?= count($grid) ?> notes</span>
  </div>
  <div class="split cols-3 ressources-grid">
    <?php foreach ($grid as $p): ?>
      <a href="<?= e(url('/ressources/' . $p['slug'])) ?>" data-filter-item="<?= e($p['tag']) ?>" data-filter-group="posts">
        <div class="eyebrow">
          <span><?= e($p['tag']) ?></span>
          <span><?= e($p['read']) ?></span>
        </div>
        <strong><?= e($p['t']) ?></strong>
        <span class="ressources-card-d"><?= e($p['d']) ?></span>
        <span class="ressources-card-foot">
          <span class="mono"><?= e($p['date']) ?></span>
          <?php if (!empty($p['interactive'])): ?><span class="ressources-live">Interactif</span><?php endif; ?>
        </span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
