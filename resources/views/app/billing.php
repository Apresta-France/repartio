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
$profile = $profile ?? [];
$subscription = $subscription ?? null;
$invoices = $invoices ?? [];
$paymentReady = !empty($paymentReady);
$subActive = is_array($subscription) && in_array((string) ($subscription['status'] ?? ''), ['active', 'trialing', 'past_due'], true);
$cancelAtEnd = $subActive && !empty($subscription['cancel_at_period_end']);
$periodEnd = !empty($subscription['current_period_end']) ? strtotime((string) $subscription['current_period_end']) : false;
$currentCycle = \App\Models\Billing::cycleFromPrice($subscription['price_code'] ?? null);
$profileType = (string) ($profile['type'] ?? 'individual');
?>
<header class="app-top">
  <div>
    <h1>Forfait & facturation</h1>
    <span class="eyebrow">Plan <?= e($plan['label']) ?> · <?= (int) $used ?> / <?= (int) $limit ?> circuit<?= $limit > 1 ? 's' : '' ?></span>
  </div>
</header>

<section class="billing-page">
  <?php if ($blocked && $reason === 'circuits'): ?>
    <div class="card billing-limit" role="status">
      <div class="billing-banner-copy">
        <span class="eyebrow">Limite atteinte</span>
        <h2>Vous ne pouvez plus ajouter de circuit sur <?= e($plan['label']) ?></h2>
        <p class="lede">Le forfait <?= e($plan['label']) ?> autorise <?= (int) $limit ?> circuit<?= $limit > 1 ? 's' : '' ?>. Vous en avez <?= (int) $used ?> actif<?= $used > 1 ? 's' : '' ?>. Pour en créer un autre, il faut un forfait avec plus d’emplacements.</p>
      </div>
    </div>
  <?php elseif ($blocked && $reason === 'invitations'): ?>
    <div class="card billing-limit" role="status">
      <div class="billing-banner-copy">
        <span class="eyebrow">Limite atteinte</span>
        <h2>Vous ne pouvez plus inviter sur <?= e($plan['label']) ?></h2>
        <p class="lede"><?= $membersLimit <= 0
            ? 'Le forfait Libre n’ouvre aucune invitation. Complet permet d’en inviter une, Foyer jusqu’à dix.'
            : 'Le forfait ' . e($plan['label']) . ' autorise ' . (int) $membersLimit . ' personne' . ($membersLimit > 1 ? 's' : '') . '. Choisissez un forfait avec plus d’invitations.' ?></p>
      </div>
    </div>
  <?php else: ?>
    <div class="card billing-current">
      <div class="billing-banner-copy">
        <span class="eyebrow">Forfait actuel</span>
        <h2><?= e($plan['label']) ?></h2>
        <p class="lede"><?= e(\App\Models\Plan::blurb($user)) ?></p>
        <?php if ($subActive && $periodEnd): ?>
          <p class="lede"><?= $cancelAtEnd
              ? 'Résilié : accès conservé jusqu’au ' . date('d/m/Y', $periodEnd) . '.'
              : 'Période en cours jusqu’au ' . date('d/m/Y', $periodEnd) . ($currentCycle === 'yearly' ? ' · annuel' : ' · mensuel') . '.' ?></p>
        <?php endif; ?>
      </div>
      <?php if ($subActive): ?>
        <div class="billing-sub-actions">
          <form method="post" action="<?= e(url('/app/forfait/portail')) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-ghost" type="submit">Carte, adresse et factures Stripe</button>
          </form>
          <?php if (!$cancelAtEnd): ?>
            <form method="post" action="<?= e(url('/app/forfait/resilier')) ?>" onsubmit="return confirm('Résilier à la fin de la période déjà réglée ?');">
              <?= csrf_field() ?>
              <button class="btn btn-ghost" type="submit">Résilier à l’échéance</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endif; ?>
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

  <form id="facturation" class="card card-pad billing-profile" method="post" action="<?= e(url('/app/forfait/facturation')) ?>">
    <?= csrf_field() ?>
    <div class="billing-profile-head">
      <div>
        <span class="eyebrow">Facturation</span>
        <h2>Coordonnées pour la facture</h2>
      </div>
      <p class="lede">Stripe Tax calcule la TVA selon le pays (et le n° TVA intra-communautaire s’il est renseigné). La facture Stripe reprend HT, TVA et TTC.</p>
    </div>
    <div class="fields-2">
      <label class="field">
        <span>Type</span>
        <select name="billing_type">
          <option value="individual" <?= $profileType !== 'company' ? 'selected' : '' ?>>Particulier</option>
          <option value="company" <?= $profileType === 'company' ? 'selected' : '' ?>>Entreprise</option>
        </select>
      </label>
      <label class="field">
        <span>E-mail de facturation</span>
        <input type="email" name="billing_email" value="<?= e((string) ($profile['email'] ?: $user['email'])) ?>" required>
      </label>
    </div>
    <div class="fields-2">
      <label class="field">
        <span>Nom</span>
        <input name="billing_name" value="<?= e((string) ($profile['name'] ?: $user['first_name'])) ?>" required>
      </label>
      <label class="field">
        <span>Raison sociale</span>
        <input name="billing_company" value="<?= e((string) ($profile['company_name'] ?? '')) ?>">
      </label>
    </div>
    <label class="field">
      <span>Adresse</span>
      <input name="billing_line1" value="<?= e((string) ($profile['line1'] ?? '')) ?>" required>
    </label>
    <div class="fields-3">
      <label class="field">
        <span>Code postal</span>
        <input name="billing_postal_code" value="<?= e((string) ($profile['postal_code'] ?? '')) ?>" required>
      </label>
      <label class="field">
        <span>Ville</span>
        <input name="billing_city" value="<?= e((string) ($profile['city'] ?? '')) ?>" required>
      </label>
      <label class="field">
        <span>Pays</span>
        <input name="billing_country" maxlength="2" value="<?= e((string) ($profile['country'] ?? 'FR')) ?>" required>
      </label>
    </div>
    <div class="fields-2">
      <label class="field">
        <span>N° TVA (optionnel)</span>
        <input name="billing_vat" value="<?= e((string) ($profile['vat_number'] ?? '')) ?>">
      </label>
      <label class="field">
        <span>SIRET (optionnel)</span>
        <input name="billing_siret" value="<?= e((string) ($profile['siret'] ?? '')) ?>">
      </label>
    </div>
    <button class="btn btn-navy" type="submit">Enregistrer les coordonnées</button>
  </form>

  <?php if ($paymentReady): ?>
    <div class="chips billing-cycle" style="background:oklch(0.94 0.01 255);padding:4px;border-radius:11px;align-self:start;">
      <button type="button" class="chip active" data-cycle="Mensuel">Mensuel</button>
      <button type="button" class="chip" data-cycle="Annuel">Annuel</button>
    </div>
  <?php endif; ?>

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
        $paid = (float) $offer['price_monthly_ht'] > 0 || (float) $offer['price_yearly_ht'] > 0;
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
          <span class="mono" data-monthly="<?= e(\App\Models\Plan::priceMonthly($slug)) ?>" data-yearly="<?= e(\App\Models\Plan::priceYearly($slug)) ?>"><?= e(\App\Models\Plan::priceMonthly($slug)) ?></span>
          <span data-unit-monthly="<?= $paid ? 'par mois' : 'pour toujours' ?>" data-unit-yearly="<?= (float) $offer['price_yearly_ht'] > 0 ? 'par an' : 'pour toujours' ?>"><?= $paid ? 'par mois' : 'pour toujours' ?></span>
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
        <?php if ($isCurrent && (!$paid || !$subActive)): ?>
          <button class="btn btn-ghost" type="button" disabled>Forfait en cours</button>
        <?php else: ?>
          <form method="post" action="<?= e(url('/app/forfait')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="plan" value="<?= e($slug) ?>">
            <input type="hidden" name="cycle" value="monthly" data-billing-cycle>
            <?php if ($reason !== ''): ?>
              <input type="hidden" name="reason" value="<?= e($reason) ?>">
            <?php endif; ?>
            <button class="btn <?= $recommended ? 'btn-orange' : 'btn-navy' ?>" type="submit" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"<?= e($slug) ?>","source":"billing"}'>
              <?php if ($isCurrent && $paid): ?>
                Changer de cycle
              <?php elseif (!$paid): ?>
                Revenir à <?= e($offer['label']) ?>
              <?php else: ?>
                <?= $paymentReady ? 'Payer ' : 'Passer en ' ?><?= e($offer['label']) ?>
              <?php endif; ?>
            </button>
          </form>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>

  <div class="card card-pad billing-invoices">
    <div class="billing-section-head">
      <div>
        <span class="eyebrow">Factures Stripe</span>
        <h2>Historique et TVA</h2>
      </div>
      <p class="lede">Chaque encaissement produit une facture Stripe (PDF + page hébergée) avec la TVA applicable. Changer de carte ou d’adresse se fait dans le portail Stripe.</p>
    </div>
    <?php if ($invoices === []): ?>
      <p class="lede">Aucune facture pour l’instant. Elle apparaîtra ici après le premier paiement.</p>
    <?php else: ?>
      <div class="billing-invoice-list">
        <?php foreach ($invoices as $invoice):
            $when = !empty($invoice['created_at']) ? strtotime((string) ($invoice['created_at'] ?? '')) : false;
            $paid = isset($invoice['amount_paid']) ? ((int) $invoice['amount_paid']) / 100 : (isset($invoice['total']) ? ((int) $invoice['total']) / 100 : 0);
            $tax = isset($invoice['tax']) ? ((int) $invoice['tax']) / 100 : (isset($invoice['amount_tax']) ? ((int) $invoice['amount_tax']) / 100 : null);
            $href = (string) ($invoice['hosted_invoice_url'] ?? $invoice['invoice_pdf'] ?? '');
            ?>
          <div>
            <strong><?= e((string) ($invoice['invoice_number'] ?? $invoice['stripe_invoice_id'] ?? 'Facture')) ?></strong>
            <span><?= $when ? date('d/m/Y', $when) : '' ?>
              · <?= e(number_format($paid, 2, ',', ' ')) ?> € TTC
              <?php if ($tax !== null): ?> dont <?= e(number_format($tax, 2, ',', ' ')) ?> € TVA<?php endif; ?></span>
            <?php if ($href !== ''): ?>
              <a href="<?= e($href) ?>" target="_blank" rel="noopener noreferrer">Ouvrir sur Stripe</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if ($subActive): ?>
      <form method="post" action="<?= e(url('/app/forfait/portail')) ?>">
        <?= csrf_field() ?>
        <button class="btn btn-ghost" type="submit">Ouvrir le portail Stripe</button>
      </form>
    <?php endif; ?>
  </div>
</section>
