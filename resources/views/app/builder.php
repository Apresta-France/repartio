<div class="builder" data-builder data-payload='<?= e(json_encode($payload, JSON_UNESCAPED_UNICODE)) ?>'>
  <aside class="builder-side">
    <div>
      <a href="<?= e(url('/app/circuits')) ?>" class="eyebrow">← Mes circuits</a>
      <div style="font-size:19px;font-weight:600;margin-top:8px;"><?= e($project['name']) ?></div>
      <div class="eyebrow">Ventilation des revenus</div>
    </div>
    <div>
      <div class="eyebrow" style="margin-bottom:8px;">Poser un bloc</div>
      <div style="display:grid;gap:6px;">
        <?php foreach ([['revenu','Revenu','R','var(--teal)'],['compte','Compte','C','var(--navy)'],['repartiteur','Répartiteur','P','var(--orange)'],['livret','Livret','L','var(--blue)'],['depense','Dépense','D','var(--red)']] as $p): ?>
          <button type="button" class="palette-item" data-add="<?= e($p[0]) ?>">
            <span class="dot" style="background:<?= $p[3] ?>"></span>
            <span><?= e($p[1]) ?></span>
            <span class="mono" style="margin-left:auto;font-size:10px;color:var(--faint);"><?= e($p[2]) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
      <p style="font-size:12.5px;color:var(--muted);">Cliquez un type pour le poser. Reliez le point droit d’un bloc au point gauche d’un autre.</p>
    </div>
    <div>
      <div class="eyebrow" style="padding-bottom:8px;">Chaque mois</div>
      <div class="kv">
        <div><span>Entrées</span><strong class="mono" data-stat="in">0 €</strong></div>
        <div><span>Dépenses</span><strong class="mono" data-stat="out">0 €</strong></div>
        <div><span>Épargné</span><strong class="mono" data-stat="saved">0 €</strong></div>
        <div><span>Non affecté</span><strong class="mono" data-stat="unassigned">0 €</strong></div>
      </div>
    </div>
    <div style="margin-top:auto;padding:15px;border-radius:12px;background:oklch(0.96 0.012 152);border:1px solid oklch(0.9 0.02 152);">
      <div style="display:flex;justify-content:space-between;"><span class="eyebrow" style="color:var(--teal-ink);">Projection</span><strong class="mono" data-stat="proj">0 €</strong></div>
    </div>
  </aside>
  <main style="flex:1;min-width:0;display:flex;flex-direction:column;">
    <header class="app-top">
      <form method="post" action="<?= e(url('/app/circuits/' . $project['id'])) ?>" data-save-form style="display:flex;align-items:center;gap:10px;flex:1;">
        <?= csrf_field() ?>
        <input name="name" data-name value="<?= e($project['name']) ?>" style="border:1px solid var(--line);border-radius:9px;padding:8px 12px;font-weight:700;">
        <input type="hidden" name="payload" data-payload-input>
        <button class="btn btn-ghost" type="button" data-clear>Vider</button>
        <button class="btn btn-orange" type="submit">Enregistrer</button>
      </form>
    </header>
    <div class="canvas-wrap" data-canvas>
      <div class="dots"></div>
      <div data-layer style="position:absolute;top:0;left:0;transform-origin:0 0;">
        <svg data-edges width="2400" height="1400" style="position:absolute;top:0;left:0;overflow:visible;pointer-events:none;"></svg>
      </div>
      <div style="position:absolute;right:18px;bottom:18px;display:flex;gap:4px;padding:4px;border-radius:11px;background:#fff;border:1px solid var(--line);">
        <button type="button" class="btn btn-ghost" data-zoom-out>−</button>
        <span class="mono" data-zoom style="min-width:42px;text-align:center;align-self:center;">85%</span>
        <button type="button" class="btn btn-ghost" data-zoom-in>+</button>
        <button type="button" class="btn btn-ghost" data-fit>Ajuster</button>
      </div>
    </div>
  </main>
</div>
