<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }
    if ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443) {
        return true;
    }
    return strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
}

function request_base_url(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host !== '') {
        return (is_https() ? 'https://' : 'http://') . $host;
    }

    $base = rtrim((string) env('APP_URL', ''), '/');
    if (is_https() && str_starts_with($base, 'http://')) {
        $base = 'https://' . substr($base, strlen('http://'));
    }
    return $base;
}

function url(string $path = ''): string
{
    $base = request_base_url();
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        return $base !== '' ? $base : '/';
    }
    return ($base !== '' ? $base : '') . $path;
}

function asset(string $path): string
{
    $rel = 'public/assets/' . ltrim($path, '/');
    $url = '/' . $rel;
    $full = BASE_PATH . '/' . $rel;
    if (is_file($full)) {
        $url .= '?v=' . filemtime($full);
    }

    return $url;
}

function env(string $key, mixed $default = null): mixed
{
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }
    $value = getenv($key);
    return $value !== false ? $value : $default;
}

function config(string $key, mixed $default = null): mixed
{
    return App\Core\Config::get($key, $default);
}

function old(string $key, mixed $default = ''): mixed
{
    static $old = null;
    if ($old === null) {
        $old = Session::flash('_old', []);
        if (!is_array($old)) {
            $old = [];
        }
    }
    return $old[$key] ?? $default;
}

function password_is_strong(string $password): bool
{
    return mb_strlen($password) >= 12
        && (bool) preg_match('/[a-z]/u', $password)
        && (bool) preg_match('/[A-Z]/u', $password)
        && (bool) preg_match('/[0-9\W]/u', $password);
}

function wants_json(): bool
{
    $accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');
    $xhr = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
    return str_contains($accept, 'application/json') || $xhr === 'xmlhttprequest';
}

function is_absolute_url(string $path): bool
{
    return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
}

function app_url(string $path = ''): string
{
    $configured = rtrim((string) config('app.url', ''), '/');
    $base = $configured !== '' ? $configured : request_base_url();
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        return $base !== '' ? $base : '/';
    }
    return ($base !== '' ? $base : '') . $path;
}

function same_origin_url(?string $candidate, string $fallback = '/'): string
{
    if ($candidate === null || $candidate === '') {
        return url($fallback);
    }
    if (str_starts_with($candidate, '/') && !str_starts_with($candidate, '//')) {
        return $candidate;
    }
    $base = request_base_url();
    $app = rtrim((string) config('app.url', ''), '/');
    foreach ([$base, $app] as $origin) {
        if ($origin !== '' && ($candidate === $origin || str_starts_with($candidate, $origin . '/'))) {
            return $candidate;
        }
    }
    return url($fallback);
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

function redirect(string $path, int $code = 302): void
{
    header('Location: ' . (is_absolute_url($path) ? $path : url($path)), true, $code);
    exit;
}

function back(string $fallback = '/'): void
{
    header('Location: ' . same_origin_url($_SERVER['HTTP_REFERER'] ?? null, $fallback), true, 302);
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

function legal_text(string $text): string
{
    $parts = preg_split('/(\[[^\]]+\]\((?:https?:\/\/[^)]+|\/[^)]+)\))/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    if ($parts === false) {
        return e($text);
    }

    $out = '';
    foreach ($parts as $part) {
        if (preg_match('/^\[([^\]]+)\]\((https?:\/\/[^)]+|\/[^)]+)\)$/', $part, $m)) {
            $href = str_starts_with($m[2], '/') ? url($m[2]) : $m[2];
            $rel = str_starts_with($m[2], 'http') ? ' rel="noopener noreferrer"' : '';
            $out .= '<a href="' . e($href) . '"' . $rel . '>' . e($m[1]) . '</a>';
            continue;
        }
        $out .= e($part);
    }

    return $out;
}

function track_rv(string $command, mixed ...$args): void
{
    $events = $_SESSION['_flash']['rv_events'] ?? [];
    if (!is_array($events)) {
        $events = [];
    }
    $events[] = [
        'command' => $command,
        'args' => array_values($args),
    ];
    App\Core\Session::flashSet('rv_events', $events);
}
