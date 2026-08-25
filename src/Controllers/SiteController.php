<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Content;
use App\Core\View;

class SiteController
{
    public function home(): void
    {
        View::render('site/home', [
            'title' => 'Chaque euro a une trajectoire',
            'nav' => '',
            'featured' => Content::featuredTemplates(),
        ], 'layouts/site');
    }

    public function fonctionnement(): void
    {
        View::render('site/fonctionnement', [
            'title' => 'Fonctionnement',
            'nav' => 'fonctionnement',
        ], 'layouts/site');
    }

    public function circuits(): void
    {
        View::render('site/circuits', [
            'title' => 'Circuits types',
            'nav' => 'circuits',
        ], 'layouts/site');
    }

    public function circuitRempli(): void
    {
        $showcase = Content::showcase();
        if (!$showcase) {
            redirect('/circuits-types');
        }

        View::render('site/circuit-rempli', [
            'title' => 'Un circuit rempli',
            'nav' => 'circuits',
            'builder' => true,
            'showcase' => true,
            'pack' => $showcase,
        ], 'layouts/site');
    }

    public function tarifs(): void
    {
        View::render('site/tarifs', [
            'title' => 'Tarifs',
            'nav' => 'tarifs',
        ], 'layouts/site');
    }

    public function donnees(): void
    {
        View::render('site/donnees', [
            'title' => 'Vos données',
            'nav' => 'donnees',
        ], 'layouts/site');
    }

    public function ressources(): void
    {
        View::render('site/ressources', [
            'title' => 'Ressources',
            'nav' => 'ressources',
            'posts' => Content::posts(),
        ], 'layouts/site');
    }

    public function article(string $slug): void
    {
        $post = Content::post($slug);
        if (!$post) {
            http_response_code(404);
            View::render('site/404', ['title' => 'Article introuvable'], 'layouts/site');
            return;
        }
        View::render('site/article', [
            'title' => $post['t'],
            'nav' => 'ressources',
            'ressources' => true,
            'post' => $post,
        ], 'layouts/site');
    }

    public function faq(): void
    {
        View::render('site/faq', [
            'title' => 'FAQ',
            'nav' => '',
            'faq' => Content::faq(),
        ], 'layouts/site');
    }

    public function mentions(): void
    {
        View::render('site/legal', [
            'title' => 'Mentions légales',
            'nav' => '',
            'doc' => Content::mentions(),
        ], 'layouts/site');
    }

    public function cgu(): void
    {
        View::render('site/legal', [
            'title' => 'Conditions générales d’utilisation',
            'nav' => '',
            'doc' => Content::cgu(),
        ], 'layouts/site');
    }

    public function cgv(): void
    {
        View::render('site/legal', [
            'title' => 'Conditions générales de vente',
            'nav' => '',
            'doc' => Content::cgv(),
        ], 'layouts/site');
    }

    public function confidentialite(): void
    {
        View::render('site/legal', [
            'title' => 'Politique de confidentialité',
            'nav' => '',
            'doc' => Content::privacy(),
        ], 'layouts/site');
    }
}
