<?php
$topics = \App\Articles::topics();
$count = count($posts);
$featuredSlug = \App\Articles::featuredSlug();
$featured = null;
$catalog = [];
foreach ($posts as $p) {
    $p['topic'] = \App\Articles::topicOf($p);
    if ($p['slug'] === $featuredSlug && $featured === null) {
        $featured = $p;
        continue;
    }
    $catalog[] = $p;
}
?>
<section class="section ressources-intro">
  <span class="eyebrow eyebrow-live">Guides</span>
  <h1 class="page-title">Comment gérer son argent</h1>
  <p class="lede">Des articles et des tutos pour poser un budget, remplir ses livrets, organiser les comptes d’un foyer. Une méthode claire, puis un circuit pour la tester.</p>
  <div class="ressources-toolbar">
    <label class="field ressources-search">
      <span class="visually-hidden">Chercher un guide</span>
      <input type="search" data-filter-search="guides" placeholder="Budget, livret, URSSAF…">
    </label>
    <div class="chips" role="group" aria-label="Filtrer par thème">
      <button type="button" class="chip active" data-filter="Tout" data-group="guides">Tout</button>
      <?php foreach ($topics as $topic): ?>
        <button type="button" class="chip" data-filter="<?= e($topic) ?>" data-group="guides"><?= e($topic) ?></button>
      <?php endforeach; ?>
      <span class="mono ressources-count" data-filter-count="guides" data-filter-one="guide" data-filter-many="guides"><?= $count ?> guides</span>
    </div>
  </div>
</section>

<section class="section ressources-catalog">
  <?php if ($featured): ?>
    <a class="ressources-featured" href="<?= e(url('/ressources/' . $featured['slug'])) ?>" data-filter-item="<?= e($featured['topic']) ?>" data-filter-group="guides" data-filter-text="<?= e($featured['t'] . ' ' . $featured['d'] . ' ' . $featured['topic'] . ' ' . $featured['tag']) ?>">
      <span class="eyebrow">Pour commencer</span>
      <strong><?= e($featured['t']) ?></strong>
      <span class="ressources-card-d"><?= e($featured['d']) ?></span>
      <span class="ressources-more">Lire le guide →</span>
    </a>
  <?php endif; ?>
  <div class="grid-3">
    <?php foreach ($catalog as $p): ?>
      <a class="card ressources-card" href="<?= e(url('/ressources/' . $p['slug'])) ?>" data-filter-item="<?= e($p['topic']) ?>" data-filter-group="guides" data-filter-text="<?= e($p['t'] . ' ' . $p['d'] . ' ' . $p['topic'] . ' ' . $p['tag']) ?>">
        <span class="ressources-card-meta">
          <span><?= e($p['topic']) ?></span>
          <span class="mono"><?= e($p['read']) ?></span>
        </span>
        <strong><?= e($p['t']) ?></strong>
        <span class="ressources-card-d"><?= e($p['d']) ?></span>
        <span class="ressources-more">Lire →</span>
      </a>
    <?php endforeach; ?>
  </div>
  <p class="ressources-empty" data-filter-empty="guides" hidden>Aucun guide pour cette recherche.</p>
</section>
