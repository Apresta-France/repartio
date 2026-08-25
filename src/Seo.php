<?php

declare(strict_types=1);

namespace App;

class Seo
{
    public const SITE_NAME = 'repartio';
    public const LOCALE = 'fr_FR';
    public const LANGUAGE = 'fr-FR';
    public const THEME_COLOR = '#f7f8fa';
    public const PUBLISHER = 'REINVENT';
    public const PUBLISHER_BRAND = 'ReInvent';
    public const PUBLISHER_URL = 'https://reinvent.fr';
    public const EMAIL = 'bonjour@repartio.fr';
    public const STREET = '486 rue Sadi Carnot';
    public const LOCALITY = 'Sainghin-en-Weppes';
    public const POSTAL = '59184';
    public const COUNTRY = 'FR';

    public static function for(string $path, array $extra = []): array
    {
        $path = self::normalizePath($path);
        $catalog = self::catalog()[$path] ?? [];

        $pageTitle = (string) ($extra['title'] ?? $catalog['title'] ?? self::SITE_NAME);
        $documentTitle = (string) ($extra['document_title'] ?? $catalog['document_title'] ?? ($pageTitle . ' — repartio.fr'));
        $description = self::excerpt((string) ($extra['description'] ?? $catalog['description'] ?? self::defaultDescription()));
        $canonical = app_url($path);
        $ogType = (string) ($extra['og_type'] ?? $catalog['og_type'] ?? 'website');
        $robots = (string) ($extra['robots'] ?? $catalog['robots'] ?? 'index, follow');
        $image = self::absoluteAsset((string) ($extra['image'] ?? 'img/og.png'));
        $published = self::isoDate($extra['published'] ?? $catalog['published'] ?? null);
        $modified = self::isoDate($extra['modified'] ?? $catalog['modified'] ?? $published);
        $breadcrumbs = $extra['breadcrumbs'] ?? $catalog['breadcrumbs'] ?? [];
        $faq = self::normalizeFaq($extra['faq'] ?? $catalog['faq'] ?? []);
        $section = (string) ($extra['section'] ?? $catalog['section'] ?? '');

        $meta = [
            'title' => $documentTitle,
            'page_title' => $pageTitle,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $robots,
            'og_type' => $ogType,
            'og_image' => $image,
            'og_locale' => self::LOCALE,
            'published_time' => $published,
            'modified_time' => $modified,
            'section' => $section,
            'breadcrumbs' => $breadcrumbs,
        ];
        $meta['json_ld'] = self::graph($path, $meta, $faq);

        return $meta;
    }

    public static function article(array $post): array
    {
        $slug = (string) ($post['slug'] ?? '');
        $path = '/ressources/' . $slug;
        $iso = self::frenchDateIso((string) ($post['date'] ?? ''));
        $modified = self::isoDate(date('Y-m-d', (int) filemtime(BASE_PATH . '/src/Articles.php')));
        if ($iso && $modified && $modified < $iso) {
            $modified = $iso;
        }

        return self::for($path, [
            'title' => (string) ($post['t'] ?? ''),
            'description' => (string) ($post['d'] ?? ''),
            'og_type' => 'article',
            'published' => $iso,
            'modified' => $modified,
            'section' => (string) ($post['topic'] ?? $post['tag'] ?? ''),
            'breadcrumbs' => [
                ['name' => 'Accueil', 'path' => '/'],
                ['name' => 'Ressources', 'path' => '/ressources'],
                ['name' => (string) ($post['t'] ?? ''), 'path' => $path],
            ],
        ]);
    }

    public static function notFound(string $title = 'Page introuvable'): array
    {
        return self::for('/404', [
            'title' => $title,
            'document_title' => $title . ' — repartio.fr',
            'description' => 'Cette page n’existe pas sur repartio.fr. Revenez à l’accueil ou parcourez la FAQ.',
            'robots' => 'noindex, follow',
        ]);
    }

    public static function emitSitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=3600');
        echo self::sitemapXml();
        exit;
    }

    public static function emitRobots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        echo self::robotsTxt();
        exit;
    }

    public static function sitemapXml(): string
    {
        $urls = [];
        foreach (self::catalog() as $path => $page) {
            if (!empty($page['noindex']) || ($page['robots'] ?? '') === 'noindex, follow') {
                continue;
            }
            $urls[] = [
                'loc' => app_url($path),
                'lastmod' => self::isoDate($page['modified'] ?? $page['published'] ?? self::contentStamp()),
                'changefreq' => $page['changefreq'] ?? 'monthly',
                'priority' => $page['priority'] ?? '0.6',
            ];
        }

        $articlesStamp = date('Y-m-d', (int) filemtime(BASE_PATH . '/src/Articles.php'));
        foreach (Articles::index() as $post) {
            $published = self::frenchDateIso((string) ($post['date'] ?? '')) ?? $articlesStamp;
            $lastmod = $articlesStamp >= $published ? $articlesStamp : $published;
            $urls[] = [
                'loc' => app_url('/ressources/' . $post['slug']),
                'lastmod' => $lastmod,
                'changefreq' => 'monthly',
                'priority' => !empty($post['featured']) ? '0.8' : '0.7',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . self::xml((string) $url['loc']) . "</loc>\n";
            $xml .= '    <lastmod>' . self::xml((string) $url['lastmod']) . "</lastmod>\n";
            $xml .= '    <changefreq>' . self::xml((string) $url['changefreq']) . "</changefreq>\n";
            $xml .= '    <priority>' . self::xml((string) $url['priority']) . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return $xml;
    }

    public static function robotsTxt(): string
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /app',
            'Disallow: /admin',
            'Disallow: /p/',
            'Disallow: /install',
            'Disallow: /connexion',
            'Disallow: /creer-un-compte',
            'Disallow: /mot-de-passe-oublie',
            'Disallow: /reinitialiser-mot-de-passe',
            'Disallow: /verifier-email',
            'Disallow: /invitation',
            'Disallow: /webhooks',
            '',
            'Sitemap: ' . app_url('/sitemap.xml'),
            '',
        ];

        return implode("\n", $lines);
    }

    public static function frenchDateIso(string $date): ?string
    {
        $months = [
            'janvier' => '01',
            'février' => '02',
            'fevrier' => '02',
            'mars' => '03',
            'avril' => '04',
            'mai' => '05',
            'juin' => '06',
            'juillet' => '07',
            'août' => '08',
            'aout' => '08',
            'septembre' => '09',
            'octobre' => '10',
            'novembre' => '11',
            'décembre' => '12',
            'decembre' => '12',
        ];
        if (!preg_match('/^(\d{1,2})\s+([a-zéûô]+)\s+(\d{4})$/iu', trim($date), $m)) {
            return null;
        }
        $month = $months[mb_strtolower($m[2])] ?? null;
        if ($month === null) {
            return null;
        }

        return sprintf('%04d-%s-%02d', (int) $m[3], $month, (int) $m[1]);
    }

    public static function excerpt(string $text, int $max = 158): string
    {
        $text = trim((string) preg_replace('/\s+/u', ' ', $text));
        if (mb_strlen($text) <= $max) {
            return $text;
        }
        $cut = mb_substr($text, 0, $max - 1);
        $space = mb_strrpos($cut, ' ');
        if ($space !== false && $space > 90) {
            $cut = mb_substr($cut, 0, $space);
        }

        return rtrim($cut, " \t.,;:") . '…';
    }

    public static function absoluteAsset(string $path): string
    {
        $rel = asset($path);
        $rel = preg_replace('/\?v=\d+$/', '', $rel) ?? $rel;

        return app_url($rel);
    }

    /** @return array<string, array<string, mixed>> */
    public static function catalog(): array
    {
        $stamp = self::contentStamp();

        return [
            '/' => [
                'title' => 'Chaque euro a une trajectoire',
                'document_title' => 'repartio — répartiteur de revenus : chaque euro a une trajectoire',
                'description' => 'Canvas pour répartir vos revenus entre comptes, livrets et dépenses. Reliez les flux, voyez ce qui reste chaque mois et où vous en serez dans cinq ans.',
                'priority' => '1.0',
                'changefreq' => 'weekly',
                'modified' => $stamp,
                'og_type' => 'website',
                'faq' => Content::homeFaq(),
            ],
            '/fonctionnement' => [
                'title' => 'Fonctionnement',
                'document_title' => 'Fonctionnement — comment câbler un circuit de revenus — repartio.fr',
                'description' => 'Un circuit se lit comme un plan de plomberie : chaque bloc reçoit un montant, en garde une part, et fait sortir le reste. On branche jusqu’à zéro euro non affecté.',
                'priority' => '0.9',
                'modified' => $stamp,
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Fonctionnement', 'path' => '/fonctionnement'],
                ],
            ],
            '/circuits-types' => [
                'title' => 'Circuits types',
                'document_title' => 'Circuits types — modèles de répartition à reprendre — repartio.fr',
                'description' => 'Circuits déjà câblés : couple, auto-entrepreneur, épargne de précaution. Ouvrez un modèle, remplacez les montants, la projection se recalcule.',
                'priority' => '0.9',
                'modified' => $stamp,
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Circuits types', 'path' => '/circuits-types'],
                ],
            ],
            '/circuit-rempli' => [
                'title' => 'Un circuit rempli',
                'document_title' => 'Un circuit rempli — foyer, zéro euro perdu — repartio.fr',
                'description' => 'Circuit commenté d’un foyer : plusieurs revenus, comptes joints, URSSAF et livrets réglementés. Parcourez-le, lancez la démo, puis reprenez-le avec vos chiffres.',
                'priority' => '0.8',
                'modified' => $stamp,
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Un circuit rempli', 'path' => '/circuit-rempli'],
                ],
            ],
            '/tarifs' => [
                'title' => 'Tarifs',
                'document_title' => 'Tarifs — plan gratuit et abonnements — repartio.fr',
                'description' => 'Le plan Libre pose un circuit, le projette sur 24 mois et permet un partage public. On facture le nombre de circuits, l’horizon, et les invitations à gérer.',
                'priority' => '0.8',
                'modified' => $stamp,
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Tarifs', 'path' => '/tarifs'],
                ],
            ],
            '/vos-donnees' => [
                'title' => 'Vos données',
                'document_title' => 'Vos données — aucune connexion bancaire — repartio.fr',
                'description' => 'repartio ne lit pas vos comptes : vous saisissez les montants à répartir. Hébergement en Suisse, export JSON et CSV, suppression immédiate du compte.',
                'priority' => '0.7',
                'modified' => $stamp,
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Vos données', 'path' => '/vos-donnees'],
                ],
            ],
            '/ressources' => [
                'title' => 'Ressources',
                'document_title' => 'Ressources — guides budget, livrets et URSSAF — repartio.fr',
                'description' => 'Articles et tutos pour poser un budget, provisionner l’URSSAF, remplir Livret A, LDDS et LEP, et organiser les comptes d’un foyer — puis tester dans un circuit.',
                'priority' => '0.9',
                'changefreq' => 'weekly',
                'modified' => date('Y-m-d', (int) filemtime(BASE_PATH . '/src/Articles.php')),
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Ressources', 'path' => '/ressources'],
                ],
            ],
            '/faq' => [
                'title' => 'FAQ',
                'document_title' => 'FAQ — questions sur le répartiteur de revenus — repartio.fr',
                'description' => 'Faut-il connecter sa banque, comment câbler un couple, que contient le plan gratuit : les réponses concrètes sur le fonctionnement de repartio.',
                'priority' => '0.8',
                'modified' => date('Y-m-d', (int) filemtime(BASE_PATH . '/src/Content.php')),
                'faq' => self::faqEntities(Content::faq()),
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'FAQ', 'path' => '/faq'],
                ],
            ],
            '/contact' => [
                'title' => 'Contact',
                'document_title' => 'Contact — une vraie personne vous répond — repartio.fr',
                'description' => 'Écrivez à l’équipe repartio : une personne lit et répond, généralement le jour même, au plus tard sous 72 heures ouvrées.',
                'priority' => '0.5',
                'modified' => $stamp,
                'og_type' => 'website',
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Contact', 'path' => '/contact'],
                ],
            ],
            '/mentions-legales' => [
                'title' => 'Mentions légales',
                'description' => Content::mentions()['lede'],
                'priority' => '0.3',
                'modified' => '2026-08-25',
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Mentions légales', 'path' => '/mentions-legales'],
                ],
            ],
            '/cgu' => [
                'title' => 'Conditions générales d’utilisation',
                'description' => Content::cgu()['lede'],
                'priority' => '0.3',
                'modified' => '2026-08-25',
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'CGU', 'path' => '/cgu'],
                ],
            ],
            '/cgv' => [
                'title' => 'Conditions générales de vente',
                'description' => Content::cgv()['lede'],
                'priority' => '0.3',
                'modified' => '2026-08-25',
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'CGV', 'path' => '/cgv'],
                ],
            ],
            '/confidentialite' => [
                'title' => 'Politique de confidentialité',
                'description' => Content::privacy()['lede'],
                'priority' => '0.4',
                'modified' => '2026-08-25',
                'breadcrumbs' => [
                    ['name' => 'Accueil', 'path' => '/'],
                    ['name' => 'Confidentialité', 'path' => '/confidentialite'],
                ],
            ],
            '/404' => [
                'title' => 'Page introuvable',
                'description' => 'Cette page n’existe pas sur repartio.fr.',
                'robots' => 'noindex, follow',
                'noindex' => true,
            ],
        ];
    }

    /** @param array<string, mixed> $meta */
    private static function graph(string $path, array $meta, array $faq): array
    {
        $origin = app_url('/');
        $orgId = $origin . '#organization';
        $siteId = $origin . '#website';
        $pageId = $meta['canonical'] . '#webpage';

        $graph = [
            [
                '@type' => 'Organization',
                '@id' => $orgId,
                'name' => self::SITE_NAME,
                'legalName' => self::PUBLISHER,
                'alternateName' => [self::PUBLISHER_BRAND, 'repartio.fr'],
                'url' => $origin,
                'email' => self::EMAIL,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => self::absoluteAsset('img/logo.png'),
                ],
                'image' => $meta['og_image'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => self::STREET,
                    'addressLocality' => self::LOCALITY,
                    'postalCode' => self::POSTAL,
                    'addressCountry' => self::COUNTRY,
                ],
                'sameAs' => [self::PUBLISHER_URL],
                'parentOrganization' => [
                    '@type' => 'Organization',
                    'name' => self::PUBLISHER,
                    'url' => self::PUBLISHER_URL,
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $siteId,
                'url' => $origin,
                'name' => self::SITE_NAME,
                'alternateName' => 'repartio.fr',
                'inLanguage' => self::LANGUAGE,
                'description' => self::defaultDescription(),
                'publisher' => ['@id' => $orgId],
            ],
        ];

        $webPage = [
            '@type' => $meta['og_type'] === 'article' ? 'Article' : 'WebPage',
            '@id' => $pageId,
            'url' => $meta['canonical'],
            'name' => $meta['page_title'],
            'headline' => $meta['page_title'],
            'description' => $meta['description'],
            'inLanguage' => self::LANGUAGE,
            'isPartOf' => ['@id' => $siteId],
            'publisher' => ['@id' => $orgId],
            'image' => $meta['og_image'],
        ];
        if ($meta['published_time']) {
            $webPage['datePublished'] = $meta['published_time'];
        }
        if ($meta['modified_time']) {
            $webPage['dateModified'] = $meta['modified_time'];
        }
        if ($meta['og_type'] === 'article') {
            $webPage['author'] = ['@id' => $orgId];
            $webPage['mainEntityOfPage'] = ['@id' => $pageId];
            if ($meta['section'] !== '') {
                $webPage['articleSection'] = $meta['section'];
            }
        }
        $graph[] = $webPage;

        if ($path === '/') {
            $graph[] = [
                '@type' => 'SoftwareApplication',
                '@id' => $origin . '#app',
                'name' => self::SITE_NAME,
                'applicationCategory' => 'FinanceApplication',
                'operatingSystem' => 'Web',
                'url' => $origin,
                'description' => self::defaultDescription(),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'EUR',
                ],
                'publisher' => ['@id' => $orgId],
            ];
        }

        if ($path === '/contact') {
            $graph[] = [
                '@type' => 'ContactPage',
                '@id' => $meta['canonical'] . '#contact',
                'url' => $meta['canonical'],
                'name' => $meta['page_title'],
                'isPartOf' => ['@id' => $siteId],
            ];
        }

        if ($faq !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => $meta['canonical'] . '#faq',
                'mainEntity' => array_map(static function (array $item): array {
                    return [
                        '@type' => 'Question',
                        'name' => $item['q'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $item['a'],
                        ],
                    ];
                }, $faq),
                'isPartOf' => ['@id' => $siteId],
            ];
        }

        if ($meta['breadcrumbs'] !== []) {
            $items = [];
            foreach (array_values($meta['breadcrumbs']) as $i => $crumb) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $crumb['name'],
                    'item' => app_url($crumb['path']),
                ];
            }
            $graph[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $meta['canonical'] . '#breadcrumb',
                'itemListElement' => $items,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /** @param list<array<int, string>> $rows */
    private static function faqEntities(array $rows): array
    {
        return self::normalizeFaq($rows);
    }

    /** @param list<array<string, string>|array<int, string>> $rows */
    private static function normalizeFaq(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            if (isset($row['q'], $row['a'])) {
                $q = trim((string) $row['q']);
                $a = trim((string) $row['a']);
            } elseif (isset($row[1], $row[2])) {
                $q = trim((string) $row[1]);
                $a = trim((string) $row[2]);
            } else {
                continue;
            }
            if ($q === '' || $a === '') {
                continue;
            }
            $out[] = ['q' => $q, 'a' => $a];
        }

        return $out;
    }

    private static function defaultDescription(): string
    {
        return 'repartio est un canvas de nœuds pour votre argent. Posez vos revenus, vos comptes, vos livrets, vos dépenses ; reliez-les ; lisez ce qui reste chaque mois et où vous en serez dans cinq ans.';
    }

    private static function contentStamp(): string
    {
        $files = [
            BASE_PATH . '/src/Content.php',
            BASE_PATH . '/src/Articles.php',
            BASE_PATH . '/resources/views/site/home.php',
        ];
        $latest = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                $latest = max($latest, (int) filemtime($file));
            }
        }

        return $latest > 0 ? date('Y-m-d', $latest) : date('Y-m-d');
    }

    private static function normalizePath(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        return rtrim($path, '/') ?: '/';
    }

    private static function isoDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $raw = (string) $value;
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $raw)) {
            return substr($raw, 0, 10);
        }

        return self::frenchDateIso($raw);
    }

    private static function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
