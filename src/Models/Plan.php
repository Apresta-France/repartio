<?php

declare(strict_types=1);

namespace App\Models;

class Plan
{
    public const LIBRE = 'libre';
    public const COMPLET = 'complet';
    public const FOYER = 'foyer';

    public const ALL = [
        self::LIBRE => [
            'slug' => self::LIBRE,
            'label' => 'Libre',
            'circuits' => 1,
            'horizon' => 24,
            'members' => 0,
            'price_monthly_ht' => 0.0,
            'price_yearly_ht' => 0.0,
        ],
        self::COMPLET => [
            'slug' => self::COMPLET,
            'label' => 'Complet',
            'circuits' => 3,
            'horizon' => 60,
            'members' => 1,
            'price_monthly_ht' => 3.9,
            'price_yearly_ht' => 39.0,
        ],
        self::FOYER => [
            'slug' => self::FOYER,
            'label' => 'Foyer',
            'circuits' => 50,
            'horizon' => 600,
            'members' => 10,
            'price_monthly_ht' => 8.9,
            'price_yearly_ht' => 89.0,
        ],
    ];

    public static function exists(string $slug): bool
    {
        return isset(self::ALL[$slug]);
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

        return self::ALL[$slug] ?? self::ALL[self::LIBRE];
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
        return match (self::slug($userOrPlan)) {
            self::LIBRE => self::COMPLET,
            self::COMPLET => self::FOYER,
            default => null,
        };
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
}
