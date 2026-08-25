<?php
$plans = \App\Models\Plan::all();
$planCount = max(1, count($plans));
?>
<section class="section" style="text-align:center;padding-bottom:40px;display:flex;flex-direction:column;align-items:center;gap:16px;">
  <span class="eyebrow eyebrow-live">Tarifs</span>
  <h1 class="page-title" style="font-size:46px;max-width:22ch;">Un circuit pour commencer. Plus de place, plus loin, en payant.</h1>
  <p class="lede">Le compte gratuit pose un circuit, le projette sur 24 mois et permet un partage public. On facture le nombre de circuits, l’horizon, et les invitations à gérer.</p>
  <div class="chips" style="background:oklch(0.94 0.01 255);padding:4px;border-radius:11px;">
    <button type="button" class="chip active" data-cycle="Mensuel">Mensuel</button>
    <button type="button" class="chip" data-cycle="Annuel">Annuel</button>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="<?= $planCount === 3 ? 'grid-3' : 'cards-fill' ?>">
    <?php foreach ($plans as $plan):
        $monthly = (float) $plan['price_monthly_ht'];
        $yearly = (float) $plan['price_yearly_ht'];
        $cta = trim((string) $plan['cta_url']) !== '' ? (string) $plan['cta_url'] : '/creer-un-compte';
        $ctaLabel = trim((string) $plan['cta_label']) !== '' ? (string) $plan['cta_label'] : 'Choisir';
        $featured = !empty($plan['featured']);
        ?>
      <div class="card" style="display:flex;flex-direction:column;<?= $featured ? 'border:2px solid var(--orange);box-shadow:0 10px 30px oklch(0.5 0.14 38 / 0.14);' : '' ?>">
        <div style="padding:24px 26px;display:flex;flex-direction:column;gap:12px;">
          <div style="display:flex;align-items:center;">
            <span class="eyebrow"><?= e($plan['label']) ?></span>
            <?php if ($featured): ?>
              <span class="chip" style="margin-left:auto;background:var(--orange);color:#fff;border-color:var(--orange);">Le plus pris</span>
            <?php endif; ?>
          </div>
          <div style="display:flex;align-items:baseline;gap:7px;margin:0;">
            <span class="mono" style="font-size:38px;font-weight:500;" data-monthly="<?= e(\App\Models\Plan::priceMonthly($plan)) ?>" data-yearly="<?= e(\App\Models\Plan::priceYearly($plan)) ?>"><?= e(\App\Models\Plan::priceMonthly($plan)) ?></span>
            <span data-unit-monthly="<?= $monthly > 0 ? 'par mois' : 'pour toujours' ?>" data-unit-yearly="<?= $yearly > 0 ? 'par an' : 'pour toujours' ?>"><?= $monthly > 0 ? 'par mois' : 'pour toujours' ?></span>
          </div>
          <p style="color:var(--muted);font-size:13.5px;"><?= e(\App\Models\Plan::blurb($plan)) ?></p>
          <a class="btn <?= $featured ? 'btn-orange' : 'btn-ghost' ?>" href="<?= e(url($cta)) ?>" style="width:100%;" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"<?= e($plan['slug']) ?>"}'><?= e($ctaLabel) ?></a>
        </div>
        <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
          <?php foreach (\App\Models\Plan::features($plan) as $f): ?>
            <div>✓ <?= e($f) ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="table">
    <div class="table-row table-compare" style="--compare-cols:<?= (int) $planCount ?>;background:oklch(0.975 0.005 250);font-family:var(--mono);font-size:10.5px;letter-spacing:.13em;text-transform:uppercase;color:var(--faint);">
      <span>Comparaison</span>
      <?php foreach ($plans as $plan): ?>
        <span style="text-align:center;"><?= e($plan['label']) ?></span>
      <?php endforeach; ?>
    </div>
    <?php
    $rows = [
        ['Circuits enregistrés', static fn (array $p): string => (string) (int) $p['circuits']],
        ['Horizon', static fn (array $p): string => \App\Models\Plan::horizonLabel($p)],
        ['Personnes invitées', static fn (array $p): string => (int) $p['members'] > 0 ? (string) (int) $p['members'] : '—'],
        ['Partage public', static fn (array $p): string => '✓'],
    ];
    foreach ($rows as [$label, $cell]): ?>
      <div class="table-row table-compare" style="--compare-cols:<?= (int) $planCount ?>;">
        <span><?= e($label) ?></span>
        <?php foreach ($plans as $plan): ?>
          <span class="mono" style="text-align:center;"><?= e($cell($plan)) ?></span>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>
