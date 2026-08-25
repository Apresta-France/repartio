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
  <div class="grid-3">
    <div class="card" style="display:flex;flex-direction:column;">
      <div style="padding:24px 26px;display:flex;flex-direction:column;gap:12px;">
        <span class="eyebrow">Libre</span>
        <div style="display:flex;align-items:baseline;gap:7px;margin:0;"><span class="mono" style="font-size:38px;font-weight:500;">0 €</span><span>pour toujours</span></div>
        <p style="color:var(--muted);font-size:13.5px;">Un circuit, deux ans de projection, et un lien de partage public.</p>
        <a class="btn btn-ghost" href="<?= e(url('/creer-un-compte')) ?>" style="width:100%;" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"libre"}'>Créer un circuit</a>
      </div>
      <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
        <?php foreach (['1 circuit enregistré','Les 5 types de blocs','Projection jusqu’à 24 mois','Partage public'] as $f): ?>
          <div>✓ <?= e($f) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="card" style="border:2px solid var(--orange);box-shadow:0 10px 30px oklch(0.5 0.14 38 / 0.14);display:flex;flex-direction:column;">
      <div style="padding:24px 26px;display:flex;flex-direction:column;gap:12px;">
        <div style="display:flex;align-items:center;"><span class="eyebrow">Complet</span><span class="chip" style="margin-left:auto;background:var(--orange);color:#fff;border-color:var(--orange);">Le plus pris</span></div>
        <div style="display:flex;align-items:baseline;gap:7px;margin:0;"><span class="mono" style="font-size:38px;font-weight:500;" data-price="complet">3,90 € HT</span><span data-unit="complet">par mois</span></div>
        <p style="color:var(--muted);font-size:13.5px;">Trois circuits, cinq ans de projection, une personne invitée.</p>
        <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>" style="width:100%;" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"complet"}'>Passer en Complet</a>
      </div>
      <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
        <?php foreach (['3 circuits enregistrés','Projection jusqu’à 60 mois','1 personne invitée à gérer','Partage public'] as $f): ?>
          <div>✓ <?= e($f) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="card" style="display:flex;flex-direction:column;">
      <div style="padding:24px 26px;display:flex;flex-direction:column;gap:12px;">
        <span class="eyebrow">Foyer</span>
        <div style="display:flex;align-items:baseline;gap:7px;margin:0;"><span class="mono" style="font-size:38px;font-weight:500;" data-price="foyer">8,90 € HT</span><span data-unit="foyer">par mois</span></div>
        <p style="color:var(--muted);font-size:13.5px;">Jusqu’à 50 circuits, 50 ans de projection, et jusqu’à 10 personnes pour gérer.</p>
        <a class="btn btn-ghost" href="<?= e(url('/contact')) ?>" style="width:100%;" data-rv="event" data-rv-name="plan_selected" data-rv-props='{"plan":"foyer"}'>Choisir Foyer</a>
      </div>
      <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
        <?php foreach (['Jusqu’à 50 circuits','Projection jusqu’à 50 ans','Jusqu’à 10 personnes invitées','Partage public'] as $f): ?>
          <div>✓ <?= e($f) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="table">
    <div class="table-row table-compare" style="background:oklch(0.975 0.005 250);font-family:var(--mono);font-size:10.5px;letter-spacing:.13em;text-transform:uppercase;color:var(--faint);">
      <span>Comparaison</span><span style="text-align:center;">Libre</span><span style="text-align:center;">Complet</span><span style="text-align:center;">Foyer</span>
    </div>
    <?php foreach ([
      ['Circuits enregistrés', '1', '3', '50'],
      ['Types de blocs', '5 / 5', '5 / 5', '5 / 5'],
      ['Horizon', '24 mois', '60 mois', '50 ans'],
      ['Personnes invitées', '—', '1', '10'],
      ['Partage public', '✓', '✓', '✓'],
    ] as $r): ?>
      <div class="table-row table-compare">
        <span><?= e($r[0]) ?></span>
        <span class="mono" style="text-align:center;"><?= e($r[1]) ?></span>
        <span class="mono" style="text-align:center;"><?= e($r[2]) ?></span>
        <span class="mono" style="text-align:center;"><?= e($r[3]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
