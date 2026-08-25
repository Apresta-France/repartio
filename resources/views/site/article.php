<?php
$tone = static function (string $c): string {
    return match ($c) {
        'teal' => 'var(--teal)',
        'orange' => 'var(--orange)',
        'blue' => 'var(--blue)',
        'red' => 'var(--red)',
        'navy' => 'var(--navy)',
        default => 'var(--faint)',
    };
};
$headingId = static function (array $block): string {
    return (string) ($block['id'] ?? slugify((string) ($block['text'] ?? '')));
};
?>
<article class="article">
  <header class="article-hero">
    <div class="article-crumb">
      <a href="<?= e(url('/ressources')) ?>">Ressources</a>
      <span aria-hidden="true">/</span>
      <span><?= e($post['topic'] ?? $post['tag']) ?></span>
    </div>
    <h1 class="page-title"><?= e($post['t']) ?></h1>
    <p class="lede"><?= e($post['d']) ?></p>
    <div class="article-meta">
      <span class="article-meta-date"><?= e($post['date']) ?> · <?= e($post['read']) ?> de lecture</span>
      <?php if (!empty($post['topics'])): ?>
        <div class="article-topics">
          <?php foreach ($post['topics'] as $topic): ?>
            <span class="chip"><?= e($topic) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <div class="article-layout">
    <nav class="article-toc" aria-label="Sommaire">
      <span class="eyebrow">Sommaire</span>
      <?php foreach ($post['toc'] as $item): ?>
        <a href="#<?= e($item['id']) ?>"><span class="mono"><?= e($item['n']) ?></span><?= e($item['label']) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="article-body">
      <?php foreach ($post['blocks'] as $block): ?>
        <?php $type = $block['type'] ?? ''; ?>
        <?php if ($type === 'h'): ?>
          <h2 id="<?= e($headingId($block)) ?>"><?= e($block['text']) ?></h2>
        <?php elseif ($type === 'p'): ?>
          <p><?= e($block['text']) ?></p>
        <?php elseif ($type === 'quote'): ?>
          <blockquote class="article-quote">
            <span><?= e($block['text']) ?></span>
            <?php if (!empty($block['by'])): ?><cite><?= e($block['by']) ?></cite><?php endif; ?>
          </blockquote>
        <?php elseif ($type === 'list'): ?>
          <div class="article-list">
            <?php foreach ($block['items'] as $item): ?>
              <div><span aria-hidden="true">—</span><span><?= e($item) ?></span></div>
            <?php endforeach; ?>
          </div>
        <?php elseif ($type === 'table'): ?>
          <?php $hasMid = !empty($block['headMid']); ?>
          <div class="article-table<?= $hasMid ? ' is-3' : '' ?>">
            <div class="article-table-head">
              <span><?= e($block['head'] ?? 'Libellé') ?></span>
              <?php if ($hasMid): ?><span><?= e($block['headMid']) ?></span><?php endif; ?>
              <span><?= e($block['headRight'] ?? 'Montant') ?></span>
            </div>
            <?php foreach ($block['rows'] as $row): ?>
              <div>
                <i style="background:<?= e($tone($row['c'] ?? '')) ?>"></i>
                <span><?= e($row['k']) ?></span>
                <?php if ($hasMid): ?><em class="mono"><?= e($row['mid'] ?? '') ?></em><?php endif; ?>
                <strong class="mono"><?= e($row['v']) ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        <?php elseif ($type === 'callout'): ?>
          <aside class="article-callout">
            <strong><?= e($block['title']) ?></strong>
            <p><?= e($block['text']) ?></p>
          </aside>
        <?php elseif ($type === 'links'): ?>
          <aside class="article-links">
            <strong><?= e($block['title'] ?? 'Sources') ?></strong>
            <?php foreach ($block['items'] as $link): ?>
              <a href="<?= e($link['href']) ?>" target="_blank" rel="noopener noreferrer"><?= e($link['label']) ?></a>
            <?php endforeach; ?>
          </aside>
        <?php elseif ($type === 'widget'): ?>
          <?php $widget = $block['id']; require BASE_PATH . '/resources/views/partials/article-widgets.php'; ?>
        <?php endif; ?>
      <?php endforeach; ?>

      <p class="article-disclaimer"><?= e($post['disclaimer']) ?></p>

      <div class="article-cta">
        <span>Cette mécanique se construit dans le canvas : posez vos montants, câblez les fils, lisez la projection.</span>
        <div class="cta-row">
          <?php if (!empty($post['cta'])): ?>
            <a class="btn btn-orange" href="<?= e(url($post['cta']['href'])) ?>"><?= e($post['cta']['label']) ?></a>
          <?php else: ?>
            <a class="btn btn-orange" href="<?= e(url('/creer-un-compte')) ?>">Ouvrir le builder</a>
          <?php endif; ?>
          <a class="btn btn-ghost" href="<?= e(url('/ressources')) ?>">← Tous les guides</a>
        </div>
      </div>
    </div>

    <aside class="article-aside">
      <?php if (!empty($post['figures'])): ?>
        <div>
          <span class="eyebrow">Le circuit en chiffres</span>
          <div class="article-figures">
            <?php foreach ($post['figures'] as $fig): ?>
              <div>
                <span><?= e($fig['k']) ?></span>
                <strong class="mono<?= ($fig['tone'] ?? '') === 'teal' ? ' is-teal' : '' ?>"><?= e($fig['v']) ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
      <div>
        <span class="eyebrow">À lire ensuite</span>
        <div class="article-next">
          <?php foreach ($post['related'] as $next): ?>
            <a href="<?= e(url('/ressources/' . $next['slug'])) ?>">
              <span class="eyebrow" style="color:var(--teal-ink);"><?= e(\App\Articles::topicOf($next)) ?></span>
              <strong><?= e($next['t']) ?></strong>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>
  </div>
</article>
