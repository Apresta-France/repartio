<section class="section" style="padding-bottom:40px;">
  <span class="eyebrow eyebrow-live">Ressources</span>
  <h1 class="page-title" style="font-size:48px;max-width:24ch;margin:12px 0;">Notes de terrain sur la répartition des revenus</h1>
  <p class="lede">Des méthodes concrètes, des règles réglementaires vérifiées, et des circuits réels commentés. Pas de conseil en placement : de la mécanique.</p>
</section>
<section class="page-split" style="border-bottom:1px solid var(--line);">
  <a href="<?= e(url('/ressources/couple-12338')) ?>" style="padding:40px 44px 44px 32px;background:var(--paper);color:inherit;display:flex;flex-direction:column;gap:14px;">
    <div class="eyebrow" style="display:flex;gap:12px;align-items:center;">
      <span style="background:var(--orange);color:#fff;border-radius:6px;padding:4px 8px;">À la une</span>
      <span style="color:var(--teal-ink);">Étude de cas</span>
      <span style="color:var(--faint);">18 août 2026 · 12 min</span>
    </div>
        <strong style="font-size:clamp(24px,5vw,34px);letter-spacing:-.035em;max-width:26ch;">Un couple, 12 338 € par mois, zéro euro non affecté</strong>
    <p class="lede">Le circuit complet d’une famille de quatre : deux salaires, une auto-entreprise, un local loué, deux comptes joints et six livrets.</p>
    <span style="font-weight:700;color:var(--orange-ink);">Lire l’étude de cas →</span>
  </a>
  <div style="padding:40px 32px;">
    <span class="eyebrow">Guides de référence</span>
    <div class="kv" style="margin-top:16px;">
      <?php foreach (['Taux et plafonds 2026','Anatomie d’un répartiteur','Passer d’un tableur à un circuit','Journal des versions'] as $g): ?>
        <div><strong><?= e($g) ?></strong></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section">
  <div class="chips" style="margin-bottom:26px;">
    <?php foreach (['Tout','Méthode','Réglementaire','Étude de cas','Produit'] as $f): ?>
      <button type="button" class="chip <?= $f === 'Tout' ? 'active' : '' ?>" data-filter="<?= e($f) ?>" data-group="posts"><?= e($f) ?></button>
    <?php endforeach; ?>
  </div>
  <div class="split cols-3">
    <?php foreach ($posts as $p): ?>
      <a href="<?= e(url('/ressources/' . $p['slug'])) ?>" data-filter-item="<?= e($p['tag']) ?>" data-filter-group="posts" style="padding:24px 26px;color:inherit;display:flex;flex-direction:column;gap:10px;">
        <div class="eyebrow" style="display:flex;"><span style="color:var(--teal-ink);"><?= e($p['tag']) ?></span><span style="margin-left:auto;color:var(--faint);"><?= e($p['read']) ?></span></div>
        <strong style="font-size:17.5px;"><?= e($p['t']) ?></strong>
        <span style="font-size:13.5px;color:var(--muted);"><?= e($p['d']) ?></span>
        <span class="mono" style="margin-top:auto;padding-top:10px;font-size:11px;color:var(--faint);"><?= e($p['date']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
