<?php
$templates = \App\Content::templates();
$categories = array_values(array_unique(array_map(static fn (array $t): string => $t['category'], $templates)));
$count = count($templates);
?>
<section class="section" style="padding-bottom:44px;">
  <span class="eyebrow eyebrow-live">Circuits types</span>
  <h1 class="page-title" style="max-width:26ch;margin:12px 0;"><?= e((string) $count) ?> circuits déjà câblés, à reprendre tels quels</h1>
  <p class="lede">Chaque modèle est un circuit complet. Vous l’ouvrez, vous remplacez les chiffres par les vôtres, et la projection se recalcule.</p>
  <div class="chips" style="margin-top:18px;">
    <?php foreach (array_merge(['Tout'], $categories) as $f): ?>
      <button type="button" class="chip <?= $f === 'Tout' ? 'active' : '' ?>" data-filter="<?= e($f) ?>" data-group="templates"><?= e($f) ?></button>
    <?php endforeach; ?>
    <span class="mono" style="margin-left:auto;font-size:11.5px;color:var(--faint);" data-filter-count="templates"><?= $count ?> élément<?= $count > 1 ? 's' : '' ?></span>
  </div>
</section>
<section class="section" style="padding-top:0;">
  <div class="grid-3">
    <?php foreach ($templates as $key => $t): ?>
      <form method="post" action="<?= e(url('/app/circuits')) ?>" class="card" data-filter-item="<?= e($t['category']) ?>" data-filter-group="templates" style="display:flex;flex-direction:column;">
        <?= csrf_field() ?>
        <input type="hidden" name="name" value="<?= e($t['title']) ?>">
        <input type="hidden" name="template" value="<?= e((string) $key) ?>">
        <?php $wires = $t['thumb']['wires']; $dots = $t['thumb']['dots']; require BASE_PATH . '/resources/views/partials/circuit-thumb.php'; ?>
        <div style="padding:16px 18px 18px;display:flex;flex-direction:column;gap:8px;flex:1;">
          <div style="display:flex;gap:10px;"><strong><?= e($t['title']) ?></strong><span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= (int) $t['blocks'] ?> blocs</span></div>
          <p style="margin:0;font-size:13px;line-height:1.5;color:var(--muted);"><?= e($t['hint']) ?></p>
          <button class="btn btn-ghost" style="margin-top:auto;" type="submit">Ouvrir dans le builder</button>
        </div>
      </form>
    <?php endforeach; ?>
  </div>
</section>
