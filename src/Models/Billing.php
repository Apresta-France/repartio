<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\ReInvent;
use RuntimeException;
use Throwable;

class Billing
{
    /**
     * @return array<string, mixed>
     */
    public static function profile(int $userId): array
    {
        $row = Database::fetch('SELECT * FROM billing_profiles WHERE user_id = ? LIMIT 1', [$userId]);
        return $row ?? [
            'user_id' => $userId,
            'type' => 'individual',
            'name' => '',
            'company_name' => '',
            'email' => '',
            'line1' => '',
            'line2' => '',
            'postal_code' => '',
            'city' => '',
            'country' => 'FR',
            'vat_number' => '',
            'siret' => '',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function subscription(int $userId): ?array
    {
        return Database::fetch('SELECT * FROM billing_subscriptions WHERE user_id = ? LIMIT 1', [$userId]);
    }

    public static function hasActiveSubscription(int $userId): bool
    {
        $sub = self::subscription($userId);
        if (!$sub) {
            return false;
        }
        $status = (string) ($sub['status'] ?? '');
        return in_array($status, ['active', 'trialing', 'past_due'], true);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function saveProfile(int $userId, array $data): void
    {
        $row = [
            'type' => in_array($data['type'] ?? '', ['individual', 'company'], true) ? (string) $data['type'] : 'individual',
            'name' => mb_substr(trim((string) ($data['name'] ?? '')), 0, 190),
            'company_name' => mb_substr(trim((string) ($data['company_name'] ?? '')), 0, 190),
            'email' => mb_substr(trim((string) ($data['email'] ?? '')), 0, 190),
            'line1' => mb_substr(trim((string) ($data['line1'] ?? '')), 0, 190),
            'line2' => mb_substr(trim((string) ($data['line2'] ?? '')), 0, 190),
            'postal_code' => mb_substr(trim((string) ($data['postal_code'] ?? '')), 0, 20),
            'city' => mb_substr(trim((string) ($data['city'] ?? '')), 0, 120),
            'country' => strtoupper(mb_substr(trim((string) ($data['country'] ?? 'FR')), 0, 2)) ?: 'FR',
            'vat_number' => mb_substr(trim((string) ($data['vat_number'] ?? '')), 0, 40),
            'siret' => mb_substr(preg_replace('/\s+/', '', (string) ($data['siret'] ?? '')) ?? '', 0, 20),
        ];

        Database::query(
            'INSERT INTO billing_profiles
                (user_id, type, name, company_name, email, line1, line2, postal_code, city, country, vat_number, siret, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE
                type = VALUES(type), name = VALUES(name), company_name = VALUES(company_name), email = VALUES(email),
                line1 = VALUES(line1), line2 = VALUES(line2), postal_code = VALUES(postal_code), city = VALUES(city),
                country = VALUES(country), vat_number = VALUES(vat_number), siret = VALUES(siret), updated_at = NOW()',
            [
                $userId, $row['type'], $row['name'], $row['company_name'], $row['email'],
                $row['line1'], $row['line2'], $row['postal_code'], $row['city'], $row['country'],
                $row['vat_number'], $row['siret'],
            ]
        );
    }

    public static function profileReady(int $userId, ?array $user = null): bool
    {
        $profile = self::profile($userId);
        $email = (string) ($profile['email'] !== '' ? $profile['email'] : ($user['email'] ?? ''));
        $name = (string) ($profile['type'] === 'company'
            ? ($profile['company_name'] ?: $profile['name'])
            : ($profile['name'] ?: ($user['first_name'] ?? '')));
        return $email !== ''
            && $name !== ''
            && $profile['line1'] !== ''
            && $profile['postal_code'] !== ''
            && $profile['city'] !== ''
            && $profile['country'] !== '';
    }

    /**
     * @param array<string, mixed> $user
     * @return array<string, mixed>
     */
    public static function customerPayload(array $user): array
    {
        $profile = self::profile((int) $user['id']);
        $email = (string) ($profile['email'] !== '' ? $profile['email'] : $user['email']);
        $name = (string) ($profile['name'] !== '' ? $profile['name'] : $user['first_name']);
        $company = (string) $profile['company_name'];
        $payload = [
            'external_account_id' => ReInvent::accountId((int) $user['id']),
            'email' => $email,
            'name' => $company !== '' ? $company : $name,
            'billing_profile' => [
                'type' => $profile['type'] ?: 'individual',
                'name' => $name,
                'email' => $email,
                'company_name' => $company,
                'address' => [
                    'line1' => $profile['line1'],
                    'line2' => $profile['line2'] ?: null,
                    'postal_code' => $profile['postal_code'],
                    'city' => $profile['city'],
                    'country' => $profile['country'] ?: 'FR',
                ],
                'vat_number' => $profile['vat_number'] ?: null,
                'siret' => $profile['siret'] ?: null,
            ],
        ];
        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public static function syncUser(int $userId): array
    {
        if (!ReInvent::enabled()) {
            return [];
        }
        $data = ReInvent::entitlements(ReInvent::accountId($userId));
        self::applyEntitlements($userId, $data);
        return $data;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function applyEntitlements(int $userId, array $data): void
    {
        $items = $data['items'] ?? $data['subscriptions'] ?? [];
        if (!is_array($items)) {
            $items = [];
        }

        $active = null;
        foreach ($items as $item) {
            if (!is_array($item) || ($item['type'] ?? '') !== 'subscription') {
                continue;
            }
            if (!empty($item['active_for_product'])) {
                $active = $item;
                break;
            }
        }

        $product = is_array($active) ? (string) ($active['product_code'] ?? '') : '';
        $status = is_array($active) ? (string) ($active['status'] ?? '') : '';
        $price = is_array($active) ? (string) ($active['price_code'] ?? '') : '';
        $periodEnd = is_array($active) ? self::normalizeDate($active['current_period_end'] ?? null) : null;
        $cancelAtEnd = is_array($active) && !empty($active['cancel_at_period_end']);
        $reinventId = is_array($active) && isset($active['id']) ? (int) $active['id'] : null;
        $stripeId = is_array($active) ? (string) ($active['stripe_subscription_id'] ?? '') : '';

        Database::query(
            'INSERT INTO billing_subscriptions
                (user_id, reinvent_id, stripe_subscription_id, product_code, price_code, status, current_period_end, cancel_at_period_end, synced_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                reinvent_id = VALUES(reinvent_id),
                stripe_subscription_id = VALUES(stripe_subscription_id),
                product_code = VALUES(product_code),
                price_code = VALUES(price_code),
                status = VALUES(status),
                current_period_end = VALUES(current_period_end),
                cancel_at_period_end = VALUES(cancel_at_period_end),
                synced_at = NOW(),
                updated_at = NOW()',
            [
                $userId,
                $reinventId,
                $stripeId !== '' ? $stripeId : null,
                $product !== '' ? $product : null,
                $price !== '' ? $price : null,
                $status !== '' ? $status : null,
                $periodEnd,
                $cancelAtEnd ? 1 : 0,
            ]
        );

        if ($product !== '' && Plan::exists($product)) {
            $current = User::find($userId);
            if ($current && (string) ($current['plan'] ?? '') !== $product) {
                User::updatePlan($userId, $product);
                try {
                    Project::log($userId, 'Forfait passé en ' . Plan::label($product) . ' (paiement)');
                } catch (Throwable) {
                }
            }
            return;
        }

        $current = User::find($userId);
        if ($current && (string) ($current['plan'] ?? Plan::LIBRE) !== Plan::LIBRE) {
            User::updatePlan($userId, Plan::LIBRE);
            try {
                Project::log($userId, 'Forfait revenu en Libre (abonnement inactif)');
            } catch (Throwable) {
            }
        }
    }

    public static function cycleFromPrice(?string $priceCode): string
    {
        if (is_string($priceCode) && str_ends_with($priceCode, '-yearly')) {
            return 'yearly';
        }
        return 'monthly';
    }

    private static function normalizeDate(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }
        $ts = strtotime($value);
        return $ts === false ? null : date('Y-m-d H:i:s', $ts);
    }

    /**
     * @param array<string, mixed> $user
     */
    public static function startCheckout(array $user, string $plan, string $cycle): string
    {
        if (!ReInvent::enabled()) {
            throw new RuntimeException('Le paiement n’est pas encore configuré.');
        }
        if (!self::profileReady((int) $user['id'], $user)) {
            throw new RuntimeException('Renseignez votre adresse de facturation avant de payer.');
        }

        $session = ReInvent::checkout(
            $plan,
            ReInvent::priceCode($plan, $cycle),
            self::customerPayload($user),
            app_url('/app/forfait/succes'),
            app_url('/app/forfait?annule=1')
        );
        $url = (string) ($session['checkout_url'] ?? '');
        if ($url === '') {
            throw new RuntimeException('Impossible d’ouvrir la page de paiement.');
        }
        return $url;
    }
}
