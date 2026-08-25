<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Content;
use App\Core\View;
use App\Seo;

class SiteController
{
    public function home(): void
    {
        $this->page('/', 'site/home', [
            'nav' => '',
            'featured' => Content::featuredTemplates(),
            'demo_video' => true,
        ]);
    }

    public function fonctionnement(): void
    {
        $this->page('/fonctionnement', 'site/fonctionnement', ['nav' => 'fonctionnement']);
    }

    public function circuits(): void
    {
        $this->page('/circuits-types', 'site/circuits', ['nav' => 'circuits']);
    }

    public function circuitRempli(): void
    {
        $showcase = Content::showcase();
        if (!$showcase) {
            redirect('/circuits-types');
        }

        $this->page('/circuit-rempli', 'site/circuit-rempli', [
            'nav' => 'circuits',
            'builder' => true,
            'showcase' => true,
            'pack' => $showcase,
        ]);
    }

    public function tarifs(): void
    {
        $this->page('/tarifs', 'site/tarifs', ['nav' => 'tarifs']);
    }

    public function donnees(): void
    {
        $this->page('/vos-donnees', 'site/donnees', ['nav' => 'donnees']);
    }

    public function ressources(): void
    {
        $this->page('/ressources', 'site/ressources', [
            'nav' => 'ressources',
            'ressources' => true,
            'posts' => Content::posts(),
        ]);
    }

    public function article(string $slug): void
    {
        if ($slug === 'journal-versions') {
            redirect('/ressources', 301);
        }
        $post = Content::post($slug);
        if (!$post) {
            http_response_code(404);
            $this->missing('Article introuvable');
            return;
        }
        $seo = Seo::article($post);
        View::render('site/article', [
            'nav' => 'ressources',
            'ressources' => true,
            'hide_crumbs' => true,
            'post' => $post,
            'seo' => $seo,
        ] + $seo, 'layouts/site');
    }

    public function faq(): void
    {
        $this->page('/faq', 'site/faq', [
            'nav' => '',
            'faq' => Content::faq(),
        ]);
    }

    public function mentions(): void
    {
        $this->legal('/mentions-legales', Content::mentions());
    }

    public function cgu(): void
    {
        $this->legal('/cgu', Content::cgu());
    }

    public function cgv(): void
    {
        $this->legal('/cgv', Content::cgv());
    }

    public function confidentialite(): void
    {
        $this->legal('/confidentialite', Content::privacy());
    }

    public function sitemap(): void
    {
        Seo::emitSitemap();
    }

    public function robots(): void
    {
        Seo::emitRobots();
    }

    /** @param array<string, mixed> $data */
    private function page(string $path, string $view, array $data = []): void
    {
        $seo = Seo::for($path, $data);
        View::render($view, $data + ['seo' => $seo] + $seo, 'layouts/site');
    }

    /** @param array<string, mixed> $doc */
    private function legal(string $path, array $doc): void
    {
        $this->page($path, 'site/legal', [
            'nav' => '',
            'doc' => $doc,
            'title' => (string) ($doc['title'] ?? ''),
            'description' => (string) ($doc['lede'] ?? ''),
        ]);
    }

    private function missing(string $title): void
    {
        $seo = Seo::notFound($title);
        View::render('site/404', ['seo' => $seo] + $seo, 'layouts/site');
    }
}
