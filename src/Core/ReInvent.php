<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class ReInvent
{
    public static function enabled(): bool
    {
        return self::apiKey() !== '' && self::platform() !== '';
    }

    public static function platform(): string
    {
        return trim((string) Config::get('reinvent.platform', env('REINVENT_PLATFORM', 'repartio')));
    }

    public static function apiKey(): string
    {
        return trim((string) Config::get('reinvent.api_key', env('REINVENT_API_KEY', '')));
    }

    public static function webhookSecret(): string
    {
        return trim((string) Config::get('reinvent.webhook_secret', env('REINVENT_WEBHOOK_SECRET', '')));
    }

    public static function baseUrl(): string
    {
        return rtrim((string) Config::get('reinvent.api_url', env('REINVENT_API_URL', 'https://secure.reinvent.fr')), '/');
    }

    public static function accountId(int $userId): string
    {
        return 'user_' . $userId;
    }

    public static function userIdFromAccount(?string $accountId): ?int
    {
        if (!is_string($accountId) || !preg_match('/^user_(\d+)$/', $accountId, $m)) {
            return null;
        }
        return (int) $m[1];
    }

    public static function priceCode(string $product, string $cycle): string
    {
        return $product . '-' . ($cycle === 'yearly' ? 'yearly' : 'monthly');
    }

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    public static function get(string $path, array $query = []): array
    {
        if ($query !== []) {
            $path .= (str_contains($path, '?') ? '&' : '?') . http_build_query($query);
        }
        return self::request('GET', $path);
    }

    /**
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     */
    public static function post(string $path, array $body, ?string $idempotencyKey = null): array
    {
        return self::request('POST', $path, $body, $idempotencyKey);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function products(): array
    {
        $res = self::get('/api/v1/products', ['platform' => self::platform()]);
        $data = $res['data'] ?? [];
        return is_array($data) ? array_values($data) : [];
    }

    /**
     * @param array<string, mixed> $customer
     * @return array<string, mixed>
     */
    public static function checkout(string $product, string $price, array $customer, ?string $successUrl = null, ?string $cancelUrl = null): array
    {
        $payload = [
            'platform' => self::platform(),
            'product' => $product,
            'price' => $price,
            'quantity' => 1,
            'customer' => $customer,
        ];
        if ($successUrl) {
            $payload['success_url'] = $successUrl;
        }
        if ($cancelUrl) {
            $payload['cancel_url'] = $cancelUrl;
        }

        $account = (string) ($customer['external_account_id'] ?? '');
        $key = 'repartio-' . $account . '-' . $price . '-' . date('YmdHis');

        try {
            $res = self::post('/api/v1/checkout', $payload, $key);
        } catch (RuntimeException $e) {
            $msg = mb_strtolower($e->getMessage());
            $urlRejected = str_contains($msg, 'success_url')
                || str_contains($msg, 'cancel_url')
                || str_contains($msg, 'domaine')
                || str_contains($msg, 'autoris');
            if (($successUrl || $cancelUrl) && $urlRejected) {
                unset($payload['success_url'], $payload['cancel_url']);
                $res = self::post('/api/v1/checkout', $payload, $key . '-default');
            } else {
                throw $e;
            }
        }

        $data = $res['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function entitlements(string $accountId): array
    {
        $res = self::get('/api/v1/entitlements', [
            'platform' => self::platform(),
            'external_account_id' => $accountId,
        ]);
        $data = $res['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function subscriptions(string $accountId): array
    {
        return self::listOrOne('/api/v1/subscriptions', [
            'platform' => self::platform(),
            'external_account_id' => $accountId,
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function invoices(string $accountId): array
    {
        return self::listOrOne('/api/v1/invoices', [
            'platform' => self::platform(),
            'external_account_id' => $accountId,
        ]);
    }

    public static function billingPortal(string $accountId, string $returnUrl): string
    {
        $res = self::post('/api/v1/billing-portal', [
            'platform' => self::platform(),
            'external_account_id' => $accountId,
            'return_url' => $returnUrl,
        ]);
        return (string) (($res['data']['portal_url'] ?? '') ?: '');
    }

    /**
     * @return array<string, mixed>
     */
    public static function cancel(string $accountId, string $product, bool $atPeriodEnd = true): array
    {
        $res = self::post('/api/v1/subscriptions/cancel', [
            'platform' => self::platform(),
            'external_account_id' => $accountId,
            'product' => $product,
            'at_period_end' => $atPeriodEnd,
        ]);
        $data = $res['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function changePrice(string $accountId, string $product, string $price): array
    {
        $res = self::post('/api/v1/subscriptions/change-price', [
            'platform' => self::platform(),
            'external_account_id' => $accountId,
            'product' => $product,
            'price' => $price,
            'proration_behavior' => 'create_prorations',
        ]);
        $data = $res['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    public static function verifySignature(string $body, ?string $header): bool
    {
        $secret = self::webhookSecret();
        if ($secret === '' || $header === null || $header === '') {
            return false;
        }
        $provided = $header;
        if (str_starts_with(strtolower($provided), 'sha256=')) {
            $provided = substr($provided, 7);
        }
        $expected = hash_hmac('sha256', $body, $secret);
        return hash_equals($expected, $provided);
    }

    /**
     * @param array<string, mixed> $query
     * @return list<array<string, mixed>>
     */
    private static function listOrOne(string $path, array $query): array
    {
        $res = self::get($path, $query);
        $data = $res['data'] ?? [];
        if (!is_array($data)) {
            return [];
        }
        if ($data === [] || array_is_list($data)) {
            return array_values($data);
        }
        return [$data];
    }

    /**
     * @param array<string, mixed>|null $body
     * @return array<string, mixed>
     */
    private static function request(string $method, string $path, ?array $body = null, ?string $idempotencyKey = null): array
    {
        if (!self::enabled()) {
            throw new RuntimeException('Le pont de paiement ReInvent n’est pas configuré.');
        }

        $url = self::baseUrl() . $path;
        $headers = [
            'Accept: application/json',
            'X-API-Key: ' . self::apiKey(),
        ];
        if ($idempotencyKey) {
            $headers[] = 'Idempotency-Key: ' . mb_substr($idempotencyKey, 0, 120);
        }

        $ch = curl_init($url);
        if ($ch === false) {
            throw new RuntimeException('Impossible d’appeler le pont de paiement.');
        }

        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];
        if ($body !== null) {
            $headers[] = 'Content-Type: application/json';
            $opts[CURLOPT_HTTPHEADER] = $headers;
            $opts[CURLOPT_POSTFIELDS] = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        curl_setopt_array($ch, $opts);

        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false || $errno !== 0) {
            throw new RuntimeException('Le pont de paiement est injoignable.');
        }

        $decoded = json_decode((string) $raw, true);
        if (!is_array($decoded)) {
            $decoded = [];
        }

        if ($status >= 400) {
            $message = (string) ($decoded['error']['message'] ?? 'Erreur du pont de paiement (' . $status . ').');
            throw new RuntimeException($message);
        }

        return $decoded;
    }
}
