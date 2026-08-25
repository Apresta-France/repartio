<?php
$retryPlan = (string) ($retryPlan ?? '');
$retryCycle = (string) ($retryCycle ?? 'monthly');
$canRetry = $retryPlan !== '' && \App\Models\Plan::exists($retryPlan);
?>
<header class="app-top">
  <div>
    <h1>Paiement interrompu</h1>
    <span class="eyebrow">Aucun débit · forfait inchangé</span>
  </div>
</header>

<section class="billing-page">
  <div class="card billing-result is-fail">
    <div class="billing-banner-copy">
      <span class="eyebrow">Échec ou annulation</span>
      <h2>Le paiement n’a pas abouti</h2>
      <p class="lede">Vous avez quitté Stripe, ou la carte a été refusée. Votre forfait n’a pas changé. Vous pouvez réessayer tout de suite, ou revenir choisir une autre offre.</p>
    </div>
    <div class="billing-sub-actions">
      <?php if ($canRetry): ?>
        <form method="post" action="<?= e(url('/app/forfait')) ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="plan" value="<?= e($retryPlan) ?>">
          <input type="hidden" name="cycle" value="<?= e($retryCycle) ?>">
          <button class="btn btn-orange" type="submit">Réessayer <?= e(\App\Models\Plan::label($retryPlan)) ?></button>
        </form>
      <?php endif; ?>
      <a class="btn btn-navy" href="<?= e(url('/app/forfait')) ?>">Choisir un forfait</a>
    </div>
  </div>
</section>
