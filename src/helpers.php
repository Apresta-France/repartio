<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim((string) env('APP_URL', ''), '/');
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        return $base ?: '/';
    }
    return ($base ?: '') . $path;
}

function asset(string $path): string
{
    return url('public/assets/' . ltrim($path, '/'));
}

function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

function config(string $key, mixed $default = null): mixed
{
    return App\Core\Config::get($key, $default);
}

function old(string $key, mixed $default = ''): mixed
{
    $old = Session::get('_old', []);
    return $old[$key] ?? $default;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Csrf::token()) . '">';
}

function method_field(string $method): string
{
    return '<input type="hidden" name="_method" value="' . e($method) . '">';
}

function flash(string $key, mixed $default = null): mixed
{
    return Session::flash($key, $default);
}

function redirect(string $path, int $code = 302): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)), true, $code);
    exit;
}

function back(string $fallback = '/'): never
{
    $ref = $_SERVER['HTTP_REFERER'] ?? url($fallback);
    header('Location: ' . $ref, true, 302);
    exit;
}

function money(float|int|string|null $amount): string
{
    $n = (float) $amount;
    $formatted = number_format($n, 0, ',', ' ');
    return $formatted . ' €';
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $letters = '';
    foreach (array_slice($parts, 0, 2) as $part) {
        $letters .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    return $letters !== '' ? $letters : 'RP';
}

function time_ago(?string $datetime): string
{
    if (!$datetime) {
        return 'jamais';
    }
    $ts = strtotime($datetime);
    if ($ts === false) {
        return 'jamais';
    }
    $diff = time() - $ts;
    if ($diff < 60) {
        return 'à l’instant';
    }
    if ($diff < 3600) {
        $m = (int) floor($diff / 60);
        return 'il y a ' . $m . ' min';
    }
    if ($diff < 86400) {
        $h = (int) floor($diff / 3600);
        return 'il y a ' . $h . ' h';
    }
    if ($diff < 86400 * 7) {
        $d = (int) floor($diff / 86400);
        return 'il y a ' . $d . ' jour' . ($d > 1 ? 's' : '');
    }
    return date('d/m/Y', $ts);
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower((string) preg_replace('/[^a-z0-9]+/i', '-', $text));
    return trim($text, '-');
}

function request_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if ($uri === '/index.php' && !empty($_SERVER['REDIRECT_URL']) && $_SERVER['REDIRECT_URL'] !== '/index.php') {
        $uri = $_SERVER['REDIRECT_URL'];
    }
    $script = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    if ($script !== '/' && $script !== '\\' && str_starts_with($uri, $script)) {
        $uri = substr($uri, strlen($script)) ?: '/';
    }
    if (str_starts_with($uri, '/index.php')) {
        $uri = substr($uri, strlen('/index.php')) ?: '/';
    }
    $uri = '/' . ltrim($uri, '/');
    return rtrim($uri, '/') ?: '/';
}

function is_installed(): bool
{
    return is_file(BASE_PATH . '/storage/installed.lock');
}
