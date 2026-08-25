<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\ReInvent;
use App\Core\Session;
use App\Core\View;
use App\Models\Access;
use App\Models\Billing;
use App\Models\Plan;
use App\Models\Project;
use App\Models\User;
use RuntimeException;
use Throwable;

class BillingController
{
    public function show(): void
    {
        $user = Auth::requireUser();
        $reason = (string) ($_GET['raison'] ?? '');
        if (!in_array($reason, ['circuits', 'invitations'], true)) {
            $reason = '';
        }
        if (!empty($_GET['annule'])) {
            redirect('/app/forfait/echec');
        }

        $sub = null;
        $invoices = [];
        try {
            $sub = Billing::subscription((int) $user['id']);
        } catch (Throwable) {
            $sub = null;
        }
        if (ReInvent::enabled()) {
            try {
                $invoices = ReInvent::invoices(ReInvent::accountId((int) $user['id']));
            } catch (Throwable) {
                $invoices = [];
            }
        }

        $needed = max(0, (int) ($_GET['besoin'] ?? 0));

        View::render('app/billing', [
            'title' => 'Forfait & facturation',
            'nav' => 'forfait',
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
            'memberCount' => Access::countForOwner((int) $user['id']),
            'reason' => $reason,
            'needed' => $needed,
            'profile' => Billing::profile((int) $user['id']),
            'subscription' => $sub,
            'invoices' => $invoices,
            'paymentReady' => ReInvent::enabled(),
        ], 'layouts/app');
    }

    public function saveProfile(): void
    {
        $user = Auth::requireUser();
        $email = trim((string) ($_POST['billing_email'] ?? ''));
        if ($email === '') {
            $email = (string) $user['email'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flashSet('error', 'Indiquez une adresse e-mail de facturation valide.');
            redirect('/app/forfait#facturation');
        }
        Billing::saveProfile((int) $user['id'], [
            'type' => (string) ($_POST['billing_type'] ?? 'individual'),
            'name' => (string) ($_POST['billing_name'] ?? $user['first_name']),
            'company_name' => (string) ($_POST['billing_company'] ?? ''),
            'email' => $email,
            'line1' => (string) ($_POST['billing_line1'] ?? ''),
            'line2' => (string) ($_POST['billing_line2'] ?? ''),
            'postal_code' => (string) ($_POST['billing_postal_code'] ?? ''),
            'city' => (string) ($_POST['billing_city'] ?? ''),
            'country' => (string) ($_POST['billing_country'] ?? 'FR'),
            'vat_number' => (string) ($_POST['billing_vat'] ?? ''),
            'siret' => (string) ($_POST['billing_siret'] ?? ''),
        ]);
        Session::flashSet('success', 'Coordonnées de facturation enregistrées.');
        redirect('/app/forfait');
    }

    public function changePlan(): void
    {
        $user = Auth::requireUser();
        $slug = (string) ($_POST['plan'] ?? '');
        $cycle = (string) ($_POST['cycle'] ?? 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        $reason = (string) ($_POST['reason'] ?? '');
        Session::set('billing_reason', $reason);
        Session::set('billing_retry_plan', $slug);
        Session::set('billing_retry_cycle', $cycle);

        if (!Plan::exists($slug)) {
            Session::flashSet('error', 'Ce forfait n’existe pas.');
            redirect('/app/forfait');
        }

        $offer = Plan::of($slug);
        $currentSlug = Plan::slug($user);
        $sub = Billing::subscription((int) $user['id']);
        $active = Billing::hasActiveSubscription((int) $user['id']);
        $currentCycle = Billing::cycleFromPrice($sub['price_code'] ?? null);
        $paid = (float) $offer['price_monthly_ht'] > 0 || (float) $offer['price_yearly_ht'] > 0;

        if ($slug === $currentSlug && (!$paid || ($active && $currentCycle === $cycle))) {
            Session::flashSet('success', 'Vous êtes déjà sur le forfait ' . Plan::label($slug) . '.');
            redirect('/app/forfait');
        }

        if (!$paid) {
            $this->applyFreePlan($user, $slug, $active, $sub);
            return;
        }

        if (!ReInvent::enabled()) {
            Session::flashSet('error', 'Le paiement n’est pas encore configuré.');
            redirect('/app/forfait');
        }

        $targetPrice = ReInvent::priceCode($slug, $cycle);

        if ($active) {
            try {
                ReInvent::changePrice(
                    ReInvent::accountId((int) $user['id']),
                    (string) ($sub['product_code'] ?? $currentSlug),
                    $targetPrice
                );
                Billing::syncUser((int) $user['id']);
                $user = User::find((int) $user['id']) ?? $user;
                Session::flashSet('success', 'Forfait mis à jour : ' . Plan::label($user) . '.');
                $this->trackPurchase($slug, $cycle);
                $this->redirectAfterChange($reason);
            } catch (RuntimeException $e) {
                Session::flashSet('error', $e->getMessage());
                redirect('/app/forfait');
            }
        }

        try {
            $url = Billing::startCheckout($user, $slug, $cycle);
        } catch (RuntimeException $e) {
            Session::flashSet('error', $e->getMessage());
            redirect('/app/forfait');
        }
        redirect($url);
    }

    public function success(): void
    {
        $user = Auth::requireUser();
        $reason = (string) Session::get('billing_reason', '');
        $paidPlan = (string) Session::get('billing_retry_plan', '');
        $paidCycle = (string) Session::get('billing_retry_cycle', 'monthly');
        Session::forget('billing_reason');
        Session::forget('billing_retry_plan');
        Session::forget('billing_retry_cycle');

        if ($paidPlan === '' && $reason === '') {
            redirect('/app/forfait');
        }

        $active = false;
        if (ReInvent::enabled()) {
            for ($i = 0; $i < 4; $i++) {
                try {
                    Billing::syncUser((int) $user['id']);
                    $user = User::find((int) $user['id']) ?? $user;
                    $active = Billing::hasActiveSubscription((int) $user['id'])
                        || (Plan::exists($paidPlan) && Plan::slug($user) === $paidPlan);
                    if ($active) {
                        break;
                    }
                } catch (Throwable) {
                }
                if ($i < 3) {
                    usleep(400000);
                }
            }
        }

        $user = User::find((int) $user['id']) ?? $user;
        if ($active) {
            if (Plan::exists($paidPlan)) {
                $this->trackPurchase($paidPlan, $paidCycle === 'yearly' ? 'yearly' : 'monthly');
            } else {
                $this->trackPurchase(
                    Plan::slug($user),
                    Billing::cycleFromPrice((Billing::subscription((int) $user['id']) ?? [])['price_code'] ?? null)
                );
            }
            (new ProjectController())->resumePendingTemplate();
        }

        View::render('app/billing-success', [
            'title' => 'Paiement confirmé',
            'nav' => 'forfait',
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'reason' => $reason,
            'activated' => $active,
        ], 'layouts/app');
    }

    public function failed(): void
    {
        $user = Auth::requireUser();
        $plan = (string) Session::get('billing_retry_plan', '');
        $cycle = (string) Session::get('billing_retry_cycle', 'monthly');
        if ($plan !== '' && !Plan::exists($plan)) {
            $plan = '';
        }
        if ($cycle !== 'yearly') {
            $cycle = 'monthly';
        }

        View::render('app/billing-failed', [
            'title' => 'Paiement interrompu',
            'nav' => 'forfait',
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'retryPlan' => $plan,
            'retryCycle' => $cycle,
        ], 'layouts/app');
    }

    public function portal(): void
    {
        $user = Auth::requireUser();
        if (!ReInvent::enabled()) {
            Session::flashSet('error', 'Le paiement n’est pas encore configuré.');
            redirect('/app/forfait');
        }
        try {
            $url = ReInvent::billingPortal(ReInvent::accountId((int) $user['id']), app_url('/app/forfait'));
        } catch (RuntimeException $e) {
            Session::flashSet('error', $e->getMessage());
            redirect('/app/forfait');
        }
        if ($url === '') {
            Session::flashSet('error', 'Impossible d’ouvrir le portail de facturation.');
            redirect('/app/forfait');
        }
        redirect($url);
    }

    public function cancel(): void
    {
        $user = Auth::requireUser();
        $sub = Billing::subscription((int) $user['id']);
        if (!ReInvent::enabled() || !$sub || empty($sub['product_code'])) {
            Session::flashSet('error', 'Aucun abonnement à résilier.');
            redirect('/app/forfait');
        }
        try {
            ReInvent::cancel(ReInvent::accountId((int) $user['id']), (string) $sub['product_code'], true);
            Billing::syncUser((int) $user['id']);
        } catch (RuntimeException $e) {
            Session::flashSet('error', $e->getMessage());
            redirect('/app/forfait');
        }
        Session::flashSet('success', 'Résiliation enregistrée. Vous gardez l’accès jusqu’à la fin de la période déjà réglée.');
        redirect('/app/forfait');
    }

    public function webhook(): void
    {
        $raw = file_get_contents('php://input') ?: '';
        $signature = (string) ($_SERVER['HTTP_X_REINVENT_SIGNATURE'] ?? '');
        if (!ReInvent::verifySignature($raw, $signature)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'signature']);
            return;
        }

        $event = json_decode($raw, true);
        if (!is_array($event)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'payload']);
            return;
        }

        $data = is_array($event['data'] ?? null) ? $event['data'] : [];
        $account = (string) ($data['external_account_id'] ?? '');
        $userId = ReInvent::userIdFromAccount($account);
        if ($userId) {
            try {
                Billing::syncUser($userId);
            } catch (Throwable) {
            }
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
    }

    /**
     * @param array<string, mixed> $user
     * @param array<string, mixed>|null $sub
     */
    /**
     * @param array<string, mixed> $user
     * @param array<string, mixed>|null $sub
     */
    private function applyFreePlan(array $user, string $slug, bool $active, ?array $sub): void
    {
        if (!Plan::exists($slug)) {
            $slug = Plan::LIBRE;
        }
        $label = Plan::label($slug);

        if ($active && ReInvent::enabled() && !empty($sub['product_code'])) {
            try {
                ReInvent::cancel(ReInvent::accountId((int) $user['id']), (string) $sub['product_code'], true);
                Billing::syncUser((int) $user['id']);
            } catch (RuntimeException $e) {
                Session::flashSet('error', $e->getMessage());
                redirect('/app/forfait');
            }
            Session::flashSet('success', 'Retour au forfait ' . $label . ' à la fin de la période déjà réglée.');
            redirect('/app/forfait');
        }

        User::updatePlan((int) $user['id'], $slug);
        Project::log((int) $user['id'], 'Forfait passé en ' . $label);
        Session::flashSet('success', 'Forfait ' . $label . ' activé. ' . Plan::blurb($slug));
        redirect('/app/circuits');
    }

    private function redirectAfterChange(string $reason): void
    {
        if ($reason === 'invitations') {
            redirect('/app/acces');
        }
        if ($reason === 'circuits') {
            redirect('/app/circuits');
        }
        redirect('/app/forfait');
    }

    private function trackPurchase(string $slug, string $cycle): void
    {
        $offer = Plan::of($slug);
        $amount = $cycle === 'yearly'
            ? (float) $offer['price_yearly_ht']
            : (float) $offer['price_monthly_ht'];
        if ($amount <= 0) {
            return;
        }
        track_rv('purchase', [
            'amount' => $amount,
            'currency' => 'EUR',
        ]);
    }
}
