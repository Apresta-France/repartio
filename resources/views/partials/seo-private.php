  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="<?= e(\App\Seo::THEME_COLOR) ?>">
  <title><?= e($title ?? 'repartio') ?> — repartio.fr</title>
  <link rel="icon" type="image/png" href="<?= e(is_file(BASE_PATH . '/public/assets/img/favicon.png') ? asset('img/favicon.png') : asset('img/logo.png')) ?>">
