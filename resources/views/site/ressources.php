<?php
$bySlug = [];
foreach ($posts as $p) {
    $bySlug[$p['slug']] = $p;
}
$doors = \App\Articles::doors();
$sections = \App\Articles::sections();
$count = count($posts);
?>
<section class="section ressources-intro">
  <span class="eyebrow eyebrow-live">Ressources</span>
  <h1 class="page-title">Notes de terrain, rangées par besoin</h1>
  <p class="lede"><?= $count ?> fiches, chacune avec un simulateur. Dites ce que vous cherchez — un tableur à quitter, un barème, un foyer déjà câblé.</p>
  <label class="field ressources-search">
    <span class="visually-hidden">Chercher une note</span>
    <input type="search" data-note-search placeholder="Chercher une note… URSSAF, livret, tableur">
  </label>
  <nav class="chips ressources-jump" aria-label="Aller à une rubrique" data-note-jump>
    <?php foreach ($sections as $section): ?>
      <a class="chip" href="#<?= e($section['id']) ?>"><?= e(preg_replace('/^\d+ · /', '', $section['kicker'])) ?></a>
    <?php endforeach; ?>
  </nav>
</section>

<section class="ressources-doors" data-note-doors>
  <?php foreach ($doors as $door): ?>
    <?php $post = $bySlug[$door['slug']] ?? null; if (!$post) continue; ?>
    <a href="<?= e(url('/ressources/' . $post['slug'])) ?>" data-note-q="<?= e(mb_strtolower($door['q'] . ' ' . $door['a'] . ' ' . $post['t'] . ' ' . $post['d'] . ' ' . $post['tag'])) ?>">
      <span class="eyebrow"><?= e($door['q']) ?></span>
      <strong><?= e($door['a']) ?></strong>
      <span class="ressources-more">Ouvrir →</span>
    </a>
  <?php endforeach; ?>
</section>

<p class="ressources-empty" data-note-empty hidden>Aucune note pour cette recherche.</p>

<?php foreach ($sections as $section): ?>
  <?php
  $items = [];
  foreach ($section['slugs'] as $slug) {
      if (isset($bySlug[$slug])) {
          $items[] = $bySlug[$slug];
      }
  }
  if (!$items) {
      continue;
  }
  ?>
  <section class="ressources-group" id="<?= e($section['id']) ?>" data-note-group>
    <div class="ressources-group-copy">
      <span class="eyebrow"><?= e($section['kicker']) ?></span>
      <h2><?= e($section['title']) ?></h2>
      <p class="lede"><?= e($section['lead']) ?></p>
    </div>
    <div class="ressources-group-list">
      <?php foreach ($items as $p): ?>
        <a href="<?= e(url('/ressources/' . $p['slug'])) ?>" data-note-q="<?= e(mb_strtolower($p['t'] . ' ' . $p['d'] . ' ' . $p['tag'] . ' ' . $section['title'] . ' ' . $section['kicker'])) ?>">
          <span class="ressources-group-meta">
            <span><?= e($p['tag']) ?></span>
            <span class="mono"><?= e($p['read']) ?></span>
          </span>
          <strong><?= e($p['t']) ?></strong>
          <span class="ressources-card-d"><?= e($p['d']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
