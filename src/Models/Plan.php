<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Throwable;

class Plan
{
    public const LIBRE = 'libre';
    public const COMPLET = 'complet';
    public const FOYER = 'foyer';

    public const DEFAULTS = [
        self::LIBRE => [
            'slug' => self::LIBRE,
            'label' => 'Libre',
            'blurb' => 'Un circuit, deux ans de projection, et un lien de partage public.',
            'circuits' => 1,
            'horizon' => 24,
            'members' => 0,
            'price_monthly_ht' => 0.0,
            'price_yearly_ht' => 0.0,
            'sort_order' => 1,
            'featured' => false,
            'cta_label' => 'Créer un circuit',
            'cta_url' => '/creer-un-compte',
        ],
        self::COMPLET => [
            'slug' => self::COMPLET,
            'label' => 'Complet',
            'blurb' => 'Trois circuits, cinq ans de projection, une personne invitée.',
            'circuits' => 3,
            'horizon' => 60,
            'members' => 1,
            'price_monthly_ht' => 3.9,
            'price_yearly_ht' => 39.0,
            'sort_order' => 2,
            'featured' => true,
            'cta_label' => 'Passer en Complet',
            'cta_url' => '/creer-un-compte',
        ],
        self::FOYER => [
            'slug' => self::FOYER,
            'label' => 'Foyer',
            'blurb' => 'Jusqu’à 50 circuits, 50 ans de projection, et jusqu’à 10 personnes pour gérer.',
            'circuits' => 50,
            'horizon' => 600,
            'members' => 10,
            'price_monthly_ht' => 8.9,
            'price_yearly_ht' => 89.0,
            'sort_order' => 3,
            'featured' => false,
            'cta_label' => 'Choisir Foyer',
            'cta_url' => '/creer-un-compte',
        ],
    ];

    /** @var array<string, array<string, mixed>>|null */
    private static ?array $catalog = null;

    public static function flush(): void
    {
        self::$catalog = null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return self::catalog();
    }

    public static function exists(string $slug): bool
    {
        return isset(self::catalog()[$slug]);
    }

    public static function find(string $slug): ?array
    {
        return self::catalog()[$slug] ?? null;
    }

    /**
     * @return list<string>
     */
    public static function features(array|string|null $userOrPlan): array
    {
        $plan = self::of($userOrPlan);
        $circuits = $plan['circuits'] === 1 ? '1 circuit' : $plan['circuits'] . ' circuits';
        $invite = match ((int) $plan['members']) {
            0 => 'Pas d’invitation',
            1 => '1 personne invitée',
            default => 'Jusqu’à ' . $plan['members'] . ' personnes invitées',
        };

        return [
            $circuits,
            'Projection jusqu’à ' . self::horizonLabel($plan['slug']),
            $invite,
            'Partage public',
        ];
    }

    public static function of(array|string|null $userOrPlan): array
    {
        if (is_array($userOrPlan)) {
            $slug = (string) ($userOrPlan['plan'] ?? $userOrPlan['slug'] ?? self::LIBRE);
        } else {
            $slug = (string) ($userOrPlan ?: self::LIBRE);
        }

        $catalog = self::catalog();
        return $catalog[$slug] ?? $catalog[self::LIBRE] ?? array_values($catalog)[0];
    }

    public static function slug(array|string|null $userOrPlan): string
    {
        return self::of($userOrPlan)['slug'];
    }

    public static function label(array|string|null $userOrPlan): string
    {
        return self::of($userOrPlan)['label'];
    }

    public static function circuitLimit(array|string|null $userOrPlan): int
    {
        return (int) self::of($userOrPlan)['circuits'];
    }

    public static function horizonMax(array|string|null $userOrPlan): int
    {
        return (int) self::of($userOrPlan)['horizon'];
    }

    public static function memberLimit(array|string|null $userOrPlan): int
    {
        return (int) self::of($userOrPlan)['members'];
    }

    public static function defaultHorizon(array|string|null $userOrPlan): int
    {
        return min(60, self::horizonMax($userOrPlan));
    }

    public static function nextSlug(array|string|null $userOrPlan): ?string
    {
        $current = self::slug($userOrPlan);
        $found = false;
        foreach (self::catalog() as $slug => $_) {
            if ($found) {
                return $slug;
            }
            if ($slug === $current) {
                $found = true;
            }
        }
        return null;
    }

    public static function nextLabel(array|string|null $userOrPlan): ?string
    {
        $next = self::nextSlug($userOrPlan);
        return $next ? self::label($next) : null;
    }

    /**
     * @return list<array{months:int,title:string,hint:string}>
     */
    public static function horizonPresets(array|string|null $userOrPlan): array
    {
        $max = self::horizonMax($userOrPlan);
        $candidates = [
            ['months' => 12, 'title' => '1 an', 'hint' => '12 mois'],
            ['months' => 24, 'title' => '2 ans', 'hint' => '24 mois'],
            ['months' => 60, 'title' => '5 ans', 'hint' => '60 mois'],
            ['months' => 600, 'title' => '50 ans', 'hint' => '600 mois'],
        ];

        return array_values(array_filter(
            $candidates,
            static fn (array $preset): bool => $preset['months'] <= $max
        ));
    }

    public static function horizonLabel(array|string|null $userOrPlan): string
    {
        $months = self::horizonMax($userOrPlan);
        if ($months >= 12 && $months % 12 === 0 && $months >= 120) {
            return ($months / 12) . ' ans';
        }
        return $months . ' mois';
    }

    public static function blurb(array|string|null $userOrPlan): string
    {
        $plan = self::of($userOrPlan);
        $stored = trim((string) ($plan['blurb'] ?? ''));
        if ($stored !== '') {
            return $stored;
        }
        $circuits = $plan['circuits'] === 1 ? '1 circuit' : $plan['circuits'] . ' circuits';
        $invite = match ((int) $plan['members']) {
            0 => 'sans invitation',
            1 => '1 personne invitée',
            default => $plan['members'] . ' personnes invitées',
        };

        return $circuits . ', projection jusqu’à ' . self::horizonLabel($plan) . ', ' . $invite . ', partage public.';
    }

    public static function formatHt(float $amount): string
    {
        if ($amount <= 0) {
            return '0 €';
        }
        $decimals = abs($amount - round($amount)) < 0.001 ? 0 : 2;
        return number_format($amount, $decimals, ',', ' ') . ' € HT';
    }

    public static function priceMonthly(array|string|null $userOrPlan): string
    {
        return self::formatHt((float) self::of($userOrPlan)['price_monthly_ht']);
    }

    public static function priceYearly(array|string|null $userOrPlan): string
    {
        return self::formatHt((float) self::of($userOrPlan)['price_yearly_ht']);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function save(string $slug, array $data, bool $create = false): void
    {
        $row = [
            'label' => mb_substr(trim((string) ($data['label'] ?? '')), 0, 80),
            'blurb' => mb_substr(trim((string) ($data['blurb'] ?? '')), 0, 255),
            'circuits' => max(1, (int) ($data['circuits'] ?? 1)),
            'horizon' => max(1, min(600, (int) ($data['horizon'] ?? 24))),
            'members' => max(0, (int) ($data['members'] ?? 0)),
            'price_monthly_ht' => max(0, (float) str_replace(',', '.', (string) ($data['price_monthly_ht'] ?? 0))),
            'price_yearly_ht' => max(0, (float) str_replace(',', '.', (string) ($data['price_yearly_ht'] ?? 0))),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'featured' => !empty($data['featured']) ? 1 : 0,
            'cta_label' => mb_substr(trim((string) ($data['cta_label'] ?? '')), 0, 80),
            'cta_url' => mb_substr(trim((string) ($data['cta_url'] ?? '')), 0, 190),
        ];

        if ($row['featured'] === 1) {
            Database::query('UPDATE plans SET featured = 0');
        }

        if ($create) {
            Database::query(
                'INSERT INTO plans (slug, label, blurb, circuits, horizon, members, price_monthly_ht, price_yearly_ht, sort_order, featured, cta_label, cta_url, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                [
                    $slug, $row['label'], $row['blurb'], $row['circuits'], $row['horizon'], $row['members'],
                    $row['price_monthly_ht'], $row['price_yearly_ht'], $row['sort_order'], $row['featured'],
                    $row['cta_label'], $row['cta_url'],
                ]
            );
        } else {
            Database::query(
                'UPDATE plans SET label = ?, blurb = ?, circuits = ?, horizon = ?, members = ?, price_monthly_ht = ?, price_yearly_ht = ?, sort_order = ?, featured = ?, cta_label = ?, cta_url = ?, updated_at = NOW()
                 WHERE slug = ?',
                [
                    $row['label'], $row['blurb'], $row['circuits'], $row['horizon'], $row['members'],
                    $row['price_monthly_ht'], $row['price_yearly_ht'], $row['sort_order'], $row['featured'],
                    $row['cta_label'], $row['cta_url'], $slug,
                ]
            );
        }
        self::flush();
    }

    public static function delete(string $slug): void
    {
        Database::query('DELETE FROM plans WHERE slug = ?', [$slug]);
        self::flush();
    }

    public static function userCount(string $slug): int
    {
        $row = Database::fetch('SELECT COUNT(*) AS n FROM users WHERE plan = ?', [$slug]);
        return (int) ($row['n'] ?? 0);
    }

    /**
     * @return array<string, int>
     */
    public static function userCounts(): array
    {
        $rows = Database::fetchAll('SELECT plan, COUNT(*) AS n FROM users GROUP BY plan');
        $out = [];
        foreach ($rows as $row) {
            $out[(string) $row['plan']] = (int) $row['n'];
        }
        return $out;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function catalog(): array
    {
        if (self::$catalog !== null) {
            return self::$catalog;
        }

        $rows = [];
        try {
            $rows = Database::fetchAll('SELECT * FROM plans ORDER BY sort_order ASC, slug ASC');
        } catch (Throwable) {
            $rows = [];
        }

        if ($rows === []) {
            $catalog = [];
            foreach (self::DEFAULTS as $slug => $plan) {
                $catalog[$slug] = self::normalize($plan);
            }
            self::$catalog = $catalog;
            return $catalog;
        }

        $catalog = [];
        foreach ($rows as $row) {
            $catalog[(string) $row['slug']] = self::normalize($row);
        }
        self::$catalog = $catalog;
        return $catalog;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function normalize(array $row): array
    {
        return [
            'slug' => (string) $row['slug'],
            'label' => (string) $row['label'],
            'blurb' => (string) ($row['blurb'] ?? ''),
            'circuits' => (int) $row['circuits'],
            'horizon' => (int) $row['horizon'],
            'members' => (int) $row['members'],
            'price_monthly_ht' => (float) $row['price_monthly_ht'],
            'price_yearly_ht' => (float) $row['price_yearly_ht'],
            'sort_order' => (int) ($row['sort_order'] ?? 0),
            'featured' => !empty($row['featured']),
            'cta_label' => (string) ($row['cta_label'] ?? ''),
            'cta_url' => (string) ($row['cta_url'] ?? ''),
        ];
    }
}
