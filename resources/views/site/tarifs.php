<section class="section" style="text-align:center;padding-bottom:40px;display:flex;flex-direction:column;align-items:center;gap:16px;">
  <span class="eyebrow">Tarifs</span>
  <h1 class="page-title" style="font-size:46px;max-width:22ch;">Le circuit est gratuit. Le confort est payant.</h1>
  <p class="lede">Tous les types de blocs, tous les plafonds réglementaires et la projection à 60 mois sont dans la version gratuite.</p>
  <div class="chips" style="background:oklch(0.94 0.01 255);padding:4px;border-radius:11px;">
    <button type="button" class="chip active" data-cycle="Mensuel">Mensuel</button>
    <button type="button" class="chip" data-cycle="Annuel">Annuel</button>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="grid-3">
    <div class="card" style="display:flex;flex-direction:column;">
      <div style="padding:24px 26px;">
        <span class="eyebrow">Libre</span>
        <div style="display:flex;align-items:baseline;gap:7px;margin:10px 0;"><span class="mono" style="font-size:38px;font-weight:500;">0 €</span><span>pour toujours</span></div>
        <p style="color:var(--muted);font-size:13.5px;">De quoi décrire entièrement un budget familial et le projeter à cinq ans.</p>
        <a class="btn btn-ghost" href="<?= e(url('/creer-un-compte')) ?>" style="width:100%;">Créer un circuit</a>
      </div>
      <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
        <?php foreach (['3 circuits enregistrés','Les 5 types de blocs','Plafonds et taux réglementaires','Projection jusqu’à 60 mois','Export JSON et CSV'] as $f): ?>
          <div>✓ <?= e($f) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="card" style="border:2px solid var(--orange);box-shadow:0 10px 30px oklch(0.5 0.14 38 / 0.14);display:flex;flex-direction:column;">
      <div style="padding:24px 26px;">
        <div style="display:flex;align-items:center;"><span class="eyebrow">Complet</span><span class="chip" style="margin-left:auto;background:var(--orange);color:#fff;border-color:var(--orange);">Le plus pris</span></div>
        <div style="display:flex;align-items:baseline;gap:7px;margin:10px 0;"><span class="mono" style="font-size:38px;font-weight:500;" data-price="complet">4,90 €</span><span data-unit="complet">par mois</span></div>
        <p style="color:var(--muted);font-size:13.5px;">Pour comparer des scénarios et suivre un circuit qui évolue.</p>
        <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>" style="width:100%;">Passer en Complet</a>
      </div>
      <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
        <?php foreach (['Circuits illimités','Scénarios comparés','Horizon jusqu’à 120 mois','Historique des versions','Lien de partage','Impression A3'] as $f): ?>
          <div>✓ <?= e($f) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="card" style="display:flex;flex-direction:column;">
      <div style="padding:24px 26px;">
        <span class="eyebrow">Foyer</span>
        <div style="display:flex;align-items:baseline;gap:7px;margin:10px 0;"><span class="mono" style="font-size:38px;font-weight:500;" data-price="foyer">7,90 €</span><span data-unit="foyer">par mois</span></div>
        <p style="color:var(--muted);font-size:13.5px;">Le même circuit, à deux, avec chacun son accès.</p>
        <a class="btn btn-ghost" href="<?= e(url('/contact')) ?>" style="width:100%;">Choisir Foyer</a>
      </div>
      <div style="padding:20px 26px;border-top:1px solid var(--line);display:flex;flex-direction:column;gap:10px;font-size:13.5px;">
        <?php foreach (['Tout le plan Complet','2 comptes liés','Blocs personnels masquables','Journal par personne','Assistance 24 h ouvrées'] as $f): ?>
          <div>✓ <?= e($f) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="table">
    <div class="table-row" style="grid-template-columns:1.6fr repeat(3,.8fr);background:oklch(0.975 0.005 250);font-family:var(--mono);font-size:10.5px;letter-spacing:.13em;text-transform:uppercase;color:var(--faint);">
      <span>Comparaison</span><span style="text-align:center;">Libre</span><span style="text-align:center;">Complet</span><span style="text-align:center;">Foyer</span>
    </div>
    <?php foreach ([
      ['Circuits enregistrés', '3', 'illimité', 'illimité'],
      ['Types de blocs', '5 / 5', '5 / 5', '5 / 5'],
      ['Horizon', '60 mois', '120 mois', '120 mois'],
      ['Scénarios comparés', '—', '✓', '✓'],
      ['Historique', '—', '✓', '✓'],
      ['Comptes liés', '1', '1', '2'],
    ] as $r): ?>
      <div class="table-row" style="grid-template-columns:1.6fr repeat(3,.8fr);">
        <span><?= e($r[0]) ?></span>
        <span class="mono" style="text-align:center;"><?= e($r[1]) ?></span>
        <span class="mono" style="text-align:center;"><?= e($r[2]) ?></span>
        <span class="mono" style="text-align:center;"><?= e($r[3]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
