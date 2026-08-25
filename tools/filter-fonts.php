<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$fontDir = $root . '/public/assets/fonts';
$cssPath = $fontDir . '/faces.css';
$appCss = $root . '/public/assets/css/app.css';

$css = (string) file_get_contents($cssPath);
$keep = '';
if (preg_match_all('/@font-face \{[^}]+\}/s', $css, $matches)) {
    foreach ($matches[0] as $face) {
        if (!str_contains($face, '-latin')) {
            continue;
        }
        if (str_contains($face, 'cyrillic') || str_contains($face, 'vietnamese')) {
            continue;
        }
        $keep .= $face . "\n";
    }
}
file_put_contents($cssPath, $keep);

foreach (glob($fontDir . '/*.woff2') ?: [] as $file) {
    $name = basename($file);
    if (!str_contains($name, '-latin') || str_contains($name, 'cyrillic') || str_contains($name, 'vietnamese')) {
        unlink($file);
    }
}

$app = (string) file_get_contents($appCss);
$marker = '/* self-hosted fonts */';
if (str_contains($app, $marker)) {
    $app = (string) preg_replace('/\/\* self-hosted fonts \*\/.*?\/\* end self-hosted fonts \*\/\n?/s', '', $app);
}

$block = $marker . "\n" . $keep . "/* end self-hosted fonts */\n\n";
file_put_contents($appCss, $block . ltrim($app));

echo "faces: " . substr_count($keep, '@font-face') . "\n";
echo "woff2: " . count(glob($fontDir . '/*.woff2') ?: []) . "\n";
