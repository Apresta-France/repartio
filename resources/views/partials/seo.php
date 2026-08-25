<?php
/** @var array<string, mixed> $seo */
$seo = is_array($seo ?? null) ? $seo : [];
$title = (string) ($seo['title'] ?? ($title ?? 'repartio'));
$description = (string) ($seo['description'] ?? '');
$canonical = (string) ($seo['canonical'] ?? app_url(request_path()));
$robots = (string) ($seo['robots'] ?? 'index, follow');
$ogType = (string) ($seo['og_type'] ?? 'website');
$ogImage = (string) ($seo['og_image'] ?? \App\Seo::absoluteAsset('img/og.png'));
$ogLocale = (string) ($seo['og_locale'] ?? \App\Seo::LOCALE);
$published = $seo['published_time'] ?? null;
$modified = $seo['modified_time'] ?? null;
$jsonLd = $seo['json_ld'] ?? null;
$favicon = asset('img/favicon.png');
$touch = asset('img/apple-touch-icon.png');
if (!is_file(BASE_PATH . '/public/assets/img/favicon.png')) {
    $favicon = asset('img/logo.png');
}
if (!is_file(BASE_PATH . '/public/assets/img/apple-touch-icon.png')) {
    $touch = asset('img/logo.png');
}
?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <?php if ($description !== ''): ?>
  <meta name="description" content="<?= e($description) ?>">
  <?php endif; ?>
  <meta name="robots" content="<?= e($robots) ?>">
  <meta name="googlebot" content="<?= e($robots) ?>">
  <meta name="author" content="repartio — REINVENT">
  <meta name="theme-color" content="<?= e(\App\Seo::THEME_COLOR) ?>">
  <meta name="color-scheme" content="light">
  <meta name="format-detection" content="telephone=no">
  <meta name="google-site-verification" content="tkoUj9yLmWAby31MzJERDUewFeRFRLVDdpSAT93VOs4">
  <link rel="canonical" href="<?= e($canonical) ?>">
  <link rel="alternate" hreflang="fr" href="<?= e($canonical) ?>">
  <link rel="alternate" hreflang="x-default" href="<?= e($canonical) ?>">
  <link rel="sitemap" type="application/xml" title="Sitemap" href="<?= e(app_url('/sitemap.xml')) ?>">
  <link rel="icon" type="image/png" href="<?= e($favicon) ?>">
  <link rel="apple-touch-icon" href="<?= e($touch) ?>">
  <meta property="og:type" content="<?= e($ogType) ?>">
  <meta property="og:site_name" content="repartio">
  <meta property="og:locale" content="<?= e($ogLocale) ?>">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:title" content="<?= e($title) ?>">
  <?php if ($description !== ''): ?>
  <meta property="og:description" content="<?= e($description) ?>">
  <?php endif; ?>
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="repartio.fr — répartiteur de revenus">
  <?php if ($ogType === 'article' && $published): ?>
  <meta property="article:published_time" content="<?= e((string) $published) ?>">
  <?php if ($modified): ?>
  <meta property="article:modified_time" content="<?= e((string) $modified) ?>">
  <?php endif; ?>
  <?php if (!empty($seo['section'])): ?>
  <meta property="article:section" content="<?= e((string) $seo['section']) ?>">
  <?php endif; ?>
  <meta property="article:author" content="repartio">
  <?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($title) ?>">
  <?php if ($description !== ''): ?>
  <meta name="twitter:description" content="<?= e($description) ?>">
  <?php endif; ?>
  <meta name="twitter:image" content="<?= e($ogImage) ?>">
  <meta name="twitter:image:alt" content="repartio.fr — répartiteur de revenus">
  <?php if (is_array($jsonLd)): ?>
  <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
  <?php endif; ?>
