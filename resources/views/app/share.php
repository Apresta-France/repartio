<header class="app-top">
  <div>
    <a href="<?= e(url('/app/circuits/' . $project['id'])) ?>" class="eyebrow">← Retour au circuit</a>
    <h1>Partager « <?= e($project['name']) ?> »</h1>
  </div>
</header>
<section class="app-page share-page">
  <div class="card share-card">
    <div class="eyebrow"><?= $share && (int) $share['enabled'] === 1 ? 'Lien public actif' : 'Lien d’aperçu' ?></div>
    <h2>Aperçu public</h2>
    <p class="lede" style="font-size:14.5px;">Générez un lien de lecture seule à identifiant unique, donnez-lui un nom, puis envoyez-le à une ou plusieurs adresses.</p>
    <?php
      $returnTo = 'page';
      require BASE_PATH . '/resources/views/partials/share-form.php';
    ?>
  </div>
</section>
