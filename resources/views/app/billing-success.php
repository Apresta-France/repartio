<?php
$reason = (string) ($reason ?? '');
$activated = !empty($activated);
$next = $reason === 'invitations' ? '/app/acces' : ($reason === 'circuits' ? '/app/circuits' : '/app/forfait');
$nextLabel = $reason === 'invitations' ? 'Inviter quelqu’un' : ($reason === 'circuits' ? 'Créer un circuit' : 'Voir mon forfait');
?>
<header class="app-top">
  <div>
    <h1>Paiement confirmé</h1>
    <span class="eyebrow"><?= $activated ? 'Forfait ' . e(\App\Models\Plan::label($user)) : 'Activation en cours' ?></span>
  </div>
</header>

<section class="billing-page">
  <div class="card billing-result is-success">
    <span class="eyebrow">Merci</span>
    <h2><?= $activated
        ? 'Le forfait ' . e(\App\Models\Plan::label($user)) . ' est actif'
        : 'Paiement reçu, activation en cours' ?></h2>
    <p class="lede"><?= $activated
        ? e(\App\Models\Plan::blurb($user))
        : 'Stripe a bien encaissé. Les droits se mettent à jour dans quelques secondes — rechargez la page si le forfait n’apparaît pas encore.' ?></p>
    <p class="lede">La facture Stripe, avec la TVA calculée sur votre adresse, arrive par e-mail et dans Forfait &amp; facturation.</p>
    <div class="billing-sub-actions">
      <a class="btn btn-orange" href="<?= e(url($next)) ?>"><?= e($nextLabel) ?></a>
      <a class="btn btn-ghost" href="<?= e(url('/app/forfait')) ?>">Gérer l’abonnement</a>
    </div>
  </div>
</section>
