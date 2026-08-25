<?php

declare(strict_types=1);

$imgDir = dirname(__DIR__) . '/public/assets/img';
$srcPath = $imgDir . '/logo.png';
$src = imagecreatefrompng($srcPath);
if ($src === false) {
    fwrite(STDERR, "Impossible d’ouvrir le logo\n");
    exit(1);
}

$srcW = imagesx($src);
$srcH = imagesy($src);
$maxW = 800;
$scale = $srcW > $maxW ? $maxW / $srcW : 1.0;
$w = (int) max(1, round($srcW * $scale));
$h = (int) max(1, round($srcH * $scale));

$dst = imagecreatetruecolor($w, $h);
imagealphablending($dst, false);
imagesavealpha($dst, true);
$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
imagealphablending($dst, true);
imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $srcW, $srcH);
imagesavealpha($dst, true);

$tmp = $imgDir . '/logo.tmp.png';
imagepng($dst, $tmp, 9);
echo "PNG 9 : " . filesize($tmp) . " octets ({$w}×{$h})\n";

if (function_exists('imagewebp')) {
    $webp = $imgDir . '/logo.webp';
    imagewebp($dst, $webp, 86);
    echo "WebP : " . filesize($webp) . " octets\n";
}

$quant = $imgDir . '/logo.q.png';
$palette = imagecreatetruecolor($w, $h);
imagealphablending($palette, false);
imagesavealpha($palette, true);
imagecopy($palette, $dst, 0, 0, 0, 0, $w, $h);
if (imagetruecolortopalette($palette, false, 64)) {
    imagesavealpha($palette, true);
    imagepng($palette, $quant, 9);
    echo "PNG palette : " . filesize($quant) . " octets\n";
    imagedestroy($palette);
}

$candidates = [$tmp];
if (is_file($imgDir . '/logo.q.png')) {
    $candidates[] = $imgDir . '/logo.q.png';
}
$best = $tmp;
$bestSize = filesize($tmp);
foreach ($candidates as $file) {
    $size = filesize($file);
    if ($size > 0 && $size < $bestSize) {
        $best = $file;
        $bestSize = $size;
    }
}

copy($best, $srcPath);
echo "logo.png final : " . filesize($srcPath) . " octets\n";

@unlink($tmp);
@unlink($imgDir . '/logo.q.png');

$ogSrc = $imgDir . '/og.png';
if (is_file($ogSrc) && function_exists('imagewebp')) {
    $og = imagecreatefrompng($ogSrc);
    if ($og) {
        imagewebp($og, $imgDir . '/og.webp', 84);
        echo "og.webp : " . filesize($imgDir . '/og.webp') . " octets\n";
        imagedestroy($og);
    }
}

imagedestroy($dst);
imagedestroy($src);
