<article class="section" style="max-width:760px;">
  <span class="eyebrow"><?= e($post['tag']) ?> · <?= e($post['read']) ?></span>
  <h1 class="page-title" style="font-size:42px;margin:12px 0;"><?= e($post['t']) ?></h1>
  <p class="lede"><?= e($post['date']) ?></p>
  <?php foreach ($post['body'] as $p): ?>
    <p style="font-size:16px;line-height:1.7;color:oklch(0.36 0.05 265);"><?= e($p) ?></p>
  <?php endforeach; ?>
  <div class="cta-row" style="margin-top:20px;">
    <?php if (!empty($post['cta'])): ?>
      <a class="btn btn-orange" href="<?= e(url($post['cta']['href'])) ?>"><?= e($post['cta']['label']) ?></a>
    <?php endif; ?>
    <a class="btn btn-ghost" href="<?= e(url('/ressources')) ?>">← Toutes les notes</a>
  </div>
</article>
