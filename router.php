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
$rootPrefix = realpath(__DIR__) . DIRECTORY_SEPARATOR;
if (
    $path !== '/'
    && $requested
    && is_file($requested)
    && (
        ($public && str_starts_with($requested, $publicPrefix))
        || (str_ends_with(strtolower($path), '.mp4') && str_starts_with($requested, $rootPrefix))
    )
) {
    return false;
}

require __DIR__ . '/index.php';
