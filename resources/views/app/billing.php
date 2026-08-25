<?php $plan = \App\Models\Plan::of($user); ?>
<header class="app-top"><h1>Forfait & facturation</h1></header>
<section style="padding:28px;max-width:720px;">
  <div class="card card-pad">
    <span class="eyebrow">Plan actuel</span>
    <h2><?= e($plan['label']) ?></h2>
    <p class="lede"><?= (int) $activeCount ?> circuit<?= (int) $activeCount > 1 ? 's' : '' ?> actif<?= (int) $activeCount > 1 ? 's' : '' ?>. <?= e(\App\Models\Plan::blurb($user)) ?></p>
    <?php if (\App\Models\Plan::nextSlug($user)): ?>
      <a class="btn btn-orange" href="<?= e(url('/tarifs')) ?>" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"upgrade","source":"billing"}'>Voir les plans payants</a>
    <?php endif; ?>
  </div>
</section>
