<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (str_contains($path, "\0") || str_contains($path, '..')) {
    http_response_code(400);
    exit;
}

$public = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'public');
$requested = realpath(__DIR__ . $path);
$publicPrefix = $public ? $public . DIRECTORY_SEPARATOR : '';
if (
    $path !== '/'
    && $public
    && $requested
    && is_file($requested)
    && str_starts_with($requested, $publicPrefix)
) {
    return false;
}

require __DIR__ . '/index.php';
