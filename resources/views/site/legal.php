<?php
$tabs = $doc['tabs'] ?? [];
$activeTab = $doc['activeTab'] ?? '';
?>
<?php if ($tabs): ?>
  <div class="legal-tabs">
    <?php foreach ($tabs as $tab): ?>
      <a class="legal-tab<?= ($tab['key'] ?? '') === $activeTab ? ' is-on' : '' ?>" href="<?= e(url((string) ($tab['href'] ?? '/'))) ?>"><?= e((string) ($tab['label'] ?? '')) ?></a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
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
        <?php foreach ($s['ps'] ?? [] as $p): ?><p class="legal-p"><?= legal_text($p) ?></p><?php endforeach; ?>
        <?php if (!empty($s['rows'])): ?>
          <div class="kv">
            <?php foreach ($s['rows'] as $r): ?><div><span class="k"><?= e($r['k']) ?></span><span><?= legal_text($r['v']) ?></span></div><?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($s['list'])): ?>
          <div class="legal-list">
            <?php foreach ($s['list'] as $item): ?>
              <div><span aria-hidden="true">—</span><span><?= legal_text($item) ?></span></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>
    <div class="legal-note">
      <p>repartio est un produit édité et propulsé par <a href="https://reinvent.fr" rel="noopener noreferrer">ReInvent</a> (REINVENT, SAS, SIREN 107 095 671). Fiche publique : <a href="https://www.pappers.fr/entreprise/reinvent-107095671" rel="noopener noreferrer">Pappers</a>.</p>
      <p>Une question sur ce document, ou une demande concernant vos données ? Écrivez-nous, nous répondons sous 72 heures ouvrées.</p>
      <a class="btn btn-navy" href="<?= e(url('/contact')) ?>">Nous contacter</a>
    </div>
  </div>
</div>
