<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$imgDir = $root . '/public/assets/img';
$fontDir = $root . '/public/assets/fonts';
$logoPath = $imgDir . '/logo.png';

if (!is_file($logoPath)) {
    fwrite(STDERR, "Logo introuvable : {$logoPath}\n");
    exit(1);
}

if (!is_dir($fontDir) && !mkdir($fontDir, 0775, true) && !is_dir($fontDir)) {
    fwrite(STDERR, "Impossible de créer {$fontDir}\n");
    exit(1);
}

$info = getimagesize($logoPath);
if ($info === false) {
    fwrite(STDERR, "Logo illisible\n");
    exit(1);
}
[$srcW, $srcH] = $info;
echo "Logo source : {$srcW}×{$srcH} (" . filesize($logoPath) . " octets)\n";

$src = imagecreatefrompng($logoPath);
if ($src === false) {
    fwrite(STDERR, "GD n’a pas pu ouvrir le logo\n");
    exit(1);
}
imagealphablending($src, true);
imagesavealpha($src, true);

$maxW = 960;
$scale = $srcW > $maxW ? $maxW / $srcW : 1.0;
$logoW = (int) max(1, round($srcW * $scale));
$logoH = (int) max(1, round($srcH * $scale));

$logo = imagecreatetruecolor($logoW, $logoH);
imagealphablending($logo, false);
imagesavealpha($logo, true);
$transparent = imagecolorallocatealpha($logo, 0, 0, 0, 127);
imagefilledrectangle($logo, 0, 0, $logoW, $logoH, $transparent);
imagealphablending($logo, true);
imagecopyresampled($logo, $src, 0, 0, 0, 0, $logoW, $logoH, $srcW, $srcH);
imagesavealpha($logo, true);

$optimized = $imgDir . '/logo.png';
imagepng($logo, $optimized, 9);
echo "Logo optimisé : {$logoW}×{$logoH} (" . filesize($optimized) . " octets)\n";

$favicon = imagecreatetruecolor(48, 48);
imagealphablending($favicon, false);
imagesavealpha($favicon, true);
imagefilledrectangle($favicon, 0, 0, 48, 48, $transparent);
imagealphablending($favicon, true);
$crop = (int) min($srcW, $srcH);
$sx = 0;
$sy = (int) max(0, ($srcH - $crop) / 2);
imagecopyresampled($favicon, $src, 4, 4, $sx, $sy, 40, 40, $crop, $crop);
imagesavealpha($favicon, true);
imagepng($favicon, $imgDir . '/favicon.png', 9);
imagedestroy($favicon);

$touch = imagecreatetruecolor(180, 180);
imagealphablending($touch, false);
imagesavealpha($touch, true);
imagefilledrectangle($touch, 0, 0, 180, 180, imagecolorallocatealpha($touch, 247, 248, 250, 0));
imagealphablending($touch, true);
imagecopyresampled($touch, $src, 18, 18, $sx, $sy, 144, 144, $crop, $crop);
imagesavealpha($touch, true);
imagepng($touch, $imgDir . '/apple-touch-icon.png', 9);
imagedestroy($touch);

$ogW = 1200;
$ogH = 630;
$og = imagecreatetruecolor($ogW, $ogH);
imagealphablending($og, true);
$bg = imagecolorallocate($og, 247, 248, 250);
imagefilledrectangle($og, 0, 0, $ogW, $ogH, $bg);
$line = imagecolorallocate($og, 232, 122, 62);
imagefilledrectangle($og, 0, $ogH - 8, $ogW, $ogH, $line);

$ogLogoW = 860;
$ogScale = $ogLogoW / $logoW;
$ogLogoH = (int) round($logoH * $ogScale);
$ox = (int) (($ogW - $ogLogoW) / 2);
$oy = (int) (($ogH - $ogLogoH) / 2) - 10;
imagecopyresampled($og, $logo, $ox, $oy, 0, 0, $ogLogoW, $ogLogoH, $logoW, $logoH);
imagepng($og, $imgDir . '/og.png', 8);
echo "og.png : " . filesize($imgDir . '/og.png') . " octets\n";

imagedestroy($og);
imagedestroy($logo);
imagedestroy($src);

$cssUrl = 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap';
$ctx = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36\r\n",
        'timeout' => 20,
    ],
]);
$css = @file_get_contents($cssUrl, false, $ctx);
if ($css === false) {
    fwrite(STDERR, "Impossible de télécharger la CSS Google Fonts (polices déjà présentes ?)\n");
    exit(0);
}

$faces = [];
if (preg_match_all('/\/\* ([^*]+) \*\/\s*@font-face \{([^}]+)\}/s', $css, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $m) {
        $subset = trim($m[1]);
        $block = $m[2];
        if (!preg_match('/font-family:\s*[\'"]([^\'"]+)/', $block, $fam)) {
            continue;
        }
        if (!preg_match('/font-weight:\s*(\d+)/', $block, $w)) {
            continue;
        }
        if (!preg_match('/src:\s*url\(([^)]+)\)/', $block, $srcMatch)) {
            continue;
        }
        if (!preg_match('/unicode-range:\s*([^;]+)/', $block, $range)) {
            continue;
        }
        $family = $fam[1];
        $weight = $w[1];
        $url = trim($srcMatch[1], '\'"');
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $family) ?? 'font');
        $file = $slug . '-' . $weight . '-' . $subset . '.woff2';
        $bin = @file_get_contents($url, false, $ctx);
        if ($bin === false) {
            echo "Échec téléchargement {$file}\n";
            continue;
        }
        file_put_contents($fontDir . '/' . $file, $bin);
        $faces[] = [
            'family' => $family,
            'weight' => $weight,
            'subset' => $subset,
            'file' => $file,
            'range' => trim($range[1]),
            'bytes' => strlen($bin),
        ];
        echo "Police {$file} (" . strlen($bin) . " octets)\n";
    }
}

$cssOut = '';
foreach ($faces as $face) {
    $cssOut .= "@font-face {\n";
    $cssOut .= '  font-family: "' . $face['family'] . "\";\n";
    $cssOut .= "  font-style: normal;\n";
    $cssOut .= '  font-weight: ' . $face['weight'] . ";\n";
    $cssOut .= "  font-display: swap;\n";
    $cssOut .= '  src: url("../fonts/' . $face['file'] . "\") format(\"woff2\");\n";
    $cssOut .= '  unicode-range: ' . $face['range'] . ";\n";
    $cssOut .= "}\n";
}

file_put_contents($fontDir . '/faces.css', $cssOut);
echo "faces.css écrit (" . count($faces) . " @font-face)\n";
