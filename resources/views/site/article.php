<article class="section" style="max-width:760px;">
  <span class="eyebrow"><?= e($post['tag']) ?> · <?= e($post['read']) ?></span>
  <h1 class="page-title" style="font-size:42px;margin:12px 0;"><?= e($post['t']) ?></h1>
  <p class="lede"><?= e($post['date']) ?></p>
  <?php foreach ($post['body'] as $p): ?>
    <p style="font-size:16px;line-height:1.7;color:oklch(0.36 0.05 265);"><?= e($p) ?></p>
  <?php endforeach; ?>
  <a class="btn btn-ghost" href="<?= e(url('/ressources')) ?>" style="margin-top:20px;">← Toutes les notes</a>
</article>
