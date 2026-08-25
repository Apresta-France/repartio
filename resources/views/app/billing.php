<?php
$plan = \App\Models\Plan::of($user);
$limit = (int) $plan['circuits'];
$membersLimit = (int) $plan['members'];
$used = (int) $activeCount;
$invited = (int) ($memberCount ?? 0);
$reason = (string) ($reason ?? '');
$atCircuitLimit = $used >= $limit;
$atInviteLimit = $membersLimit <= 0 || $invited >= $membersLimit;
$blocked = $reason === 'invitations' ? $atInviteLimit : $atCircuitLimit;
$neededCircuits = $reason === 'circuits' ? $used + 1 : 0;
$neededMembers = $reason === 'invitations' ? $invited + 1 : 0;
?>
<header class="app-top">
  <div>
    <h1>Changer de forfait</h1>
    <span class="eyebrow">Plan <?= e($plan['label']) ?> · <?= (int) $used ?> / <?= (int) $limit ?> circuit<?= $limit > 1 ? 's' : '' ?></span>
  </div>
</header>

<section class="billing-page">
  <?php if ($blocked && $reason === 'circuits'): ?>
    <div class="card billing-limit" role="status">
      <span class="eyebrow">Limite atteinte</span>
      <h2>Vous ne pouvez plus ajouter de circuit sur <?= e($plan['label']) ?></h2>
      <p class="lede">Le forfait <?= e($plan['label']) ?> autorise <?= (int) $limit ?> circuit<?= $limit > 1 ? 's' : '' ?>. Vous en avez <?= (int) $used ?> actif<?= $used > 1 ? 's' : '' ?>. Pour en créer un autre, il faut un forfait avec plus d’emplacements.</p>
    </div>
  <?php elseif ($blocked && $reason === 'invitations'): ?>
    <div class="card billing-limit" role="status">
      <span class="eyebrow">Limite atteinte</span>
      <h2>Vous ne pouvez plus inviter sur <?= e($plan['label']) ?></h2>
      <p class="lede"><?= $membersLimit <= 0
          ? 'Le forfait Libre n’ouvre aucune invitation. Complet permet d’en inviter une, Foyer jusqu’à dix.'
          : 'Le forfait ' . e($plan['label']) . ' autorise ' . (int) $membersLimit . ' personne' . ($membersLimit > 1 ? 's' : '') . '. Choisissez un forfait avec plus d’invitations.' ?></p>
    </div>
  <?php else: ?>
    <div class="card billing-current">
      <span class="eyebrow">Forfait actuel</span>
      <h2><?= e($plan['label']) ?></h2>
      <p class="lede"><?= e(\App\Models\Plan::blurb($user)) ?></p>
    </div>
  <?php endif; ?>

  <div class="billing-usage">
    <div>
      <span>Circuits actifs</span>
      <strong class="mono<?= $atCircuitLimit ? ' is-over' : '' ?>"><?= (int) $used ?> / <?= (int) $limit ?></strong>
    </div>
    <div>
      <span>Projection</span>
      <strong class="mono"><?= e(\App\Models\Plan::horizonLabel($user)) ?></strong>
    </div>
    <div>
      <span>Personnes invitées</span>
      <strong class="mono<?= $reason === 'invitations' && $atInviteLimit ? ' is-over' : '' ?>"><?= (int) $invited ?> / <?= (int) $membersLimit ?></strong>
    </div>
  </div>

  <div class="billing-plans">
    <?php foreach (\App\Models\Plan::all() as $slug => $offer):
        $isCurrent = $slug === $plan['slug'];
        $unlocksCircuit = $neededCircuits > 0 && (int) $offer['circuits'] >= $neededCircuits;
        $unlocksInvite = $neededMembers > 0 && (int) $offer['members'] >= $neededMembers;
        $recommended = !$isCurrent && (($reason === 'circuits' && $unlocksCircuit) || ($reason === 'invitations' && $unlocksInvite));
        $stillShort = !$isCurrent && (
            ($reason === 'circuits' && (int) $offer['circuits'] < $neededCircuits)
            || ($reason === 'invitations' && (int) $offer['members'] < $neededMembers)
        );
        ?>
      <article class="card billing-plan<?= $isCurrent ? ' is-current' : '' ?><?= $recommended ? ' is-recommended' : '' ?>">
        <div class="billing-plan-head">
          <span class="eyebrow"><?= e($offer['label']) ?></span>
          <?php if ($isCurrent): ?>
            <span class="chip">Actuel</span>
          <?php elseif ($recommended): ?>
            <span class="chip billing-chip-reco">Débloque</span>
          <?php endif; ?>
        </div>
        <div class="billing-plan-price">
          <span class="mono"><?= e(\App\Models\Plan::priceMonthly($slug)) ?></span>
          <span><?= (float) $offer['price_monthly_ht'] > 0 ? 'par mois' : 'pour toujours' ?></span>
        </div>
        <ul>
          <?php foreach (\App\Models\Plan::features($slug) as $feature): ?>
            <li><?= e($feature) ?></li>
          <?php endforeach; ?>
        </ul>
        <?php if ($stillShort && $reason === 'circuits'): ?>
          <p class="billing-plan-note">Toujours <?= (int) $offer['circuits'] ?> circuit<?= (int) $offer['circuits'] > 1 ? 's' : '' ?> — pas assez pour en créer un nouveau.</p>
        <?php elseif ($stillShort && $reason === 'invitations'): ?>
          <p class="billing-plan-note">Toujours <?= (int) $offer['members'] ?> invitation<?= (int) $offer['members'] > 1 ? 's' : '' ?> — pas assez pour en ajouter une.</p>
        <?php endif; ?>
        <?php if ($isCurrent): ?>
          <button class="btn btn-ghost" type="button" disabled>Forfait en cours</button>
        <?php else: ?>
          <form method="post" action="<?= e(url('/app/forfait')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="plan" value="<?= e($slug) ?>">
            <?php if ($reason !== ''): ?>
              <input type="hidden" name="reason" value="<?= e($reason) ?>">
            <?php endif; ?>
            <button class="btn <?= $recommended ? 'btn-orange' : 'btn-navy' ?>" type="submit" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"<?= e($slug) ?>","source":"billing"}'>
              Passer en <?= e($offer['label']) ?>
            </button>
          </form>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
</section>
