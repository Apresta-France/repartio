<section class="section" style="padding-bottom:40px;">
  <span class="eyebrow"><?= e($doc['eyebrow']) ?></span>
  <h1 class="page-title" style="font-size:42px;max-width:26ch;margin:12px 0;"><?= e($doc['title']) ?></h1>
  <p class="lede"><?= e($doc['lede']) ?></p>
  <div class="chips" style="margin-top:10px;">
    <?php foreach ($doc['meta'] as $m): ?><span class="chip"><?= e($m) ?></span><?php endforeach; ?>
  </div>
</section>
<div class="legal-layout">
  <nav class="legal-toc">
    <span class="eyebrow">Sommaire</span>
    <?php foreach ($doc['sections'] as $i => $s): ?>
      <a href="#s<?= $i ?>"><?= e(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)) ?> <?= e($s['h']) ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="legal-body">
    <?php foreach ($doc['sections'] as $i => $s): ?>
      <section id="s<?= $i ?>">
        <h2><?= e($s['h']) ?></h2>
        <?php foreach ($s['ps'] as $p): ?><p style="font-size:14.5px;line-height:1.68;color:oklch(0.36 0.05 265);"><?= e($p) ?></p><?php endforeach; ?>
        <?php if (!empty($s['rows'])): ?>
          <div class="kv">
            <?php foreach ($s['rows'] as $r): ?><div><span class="k"><?= e($r['k']) ?></span><span><?= e($r['v']) ?></span></div><?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>
  </div>
</div>
