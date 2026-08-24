<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $data = [], ?string $layout = null): void
    {
        $content = self::capture($view, $data);
        if ($layout) {
            $data['content'] = $content;
            echo self::capture($layout, $data);
            return;
        }
        echo $content;
    }

    public static function html(string $view, array $data = [], ?string $layout = null): string
    {
        $content = self::capture($view, $data);
        if ($layout) {
            $data['content'] = $content;
            return self::capture($layout, $data);
        }
        return $content;
    }

    private static function capture(string $view, array $data): string
    {
        $file = BASE_PATH . '/resources/views/' . $view . '.php';
        if (!is_file($file) && str_starts_with($view, 'emails/')) {
            $file = BASE_PATH . '/resources/' . $view . '.php';
        }
        if (!is_file($file)) {
            throw new \RuntimeException('Vue introuvable : ' . $view);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
