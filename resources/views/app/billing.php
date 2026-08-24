<header class="app-top"><h1>Forfait & facturation</h1></header>
<section style="padding:28px;max-width:720px;">
  <div class="card card-pad">
    <span class="eyebrow">Plan actuel</span>
    <h2><?= e(ucfirst($user['plan'])) ?></h2>
    <p class="lede"><?= (int) $activeCount ?> circuits actifs. Le plan Libre autorise 3 circuits, tous les blocs et la projection à 60 mois.</p>
    <a class="btn btn-orange" href="<?= e(url('/tarifs')) ?>" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"upgrade","source":"billing"}'>Voir les plans payants</a>
  </div>
</section>
