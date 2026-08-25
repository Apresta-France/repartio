<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\EnvFile;
use App\Core\Mailer;
use App\Core\Session;
use App\Core\View;
use App\Models\Access;
use App\Models\Plan;
use App\Models\Project;
use App\Models\User;
use Throwable;

class AdminController
{
    public function dashboard(): void
    {
        $user = Auth::requireAdmin();
        $plans = Plan::all();
        $counts = Plan::userCounts();
        View::render('admin/dashboard', [
            'title' => 'Administration',
            'nav' => 'dashboard',
            'user' => $user,
            'stats' => [
                'clients' => User::count(),
                'admins' => User::countAdmins(),
                'verified' => User::countVerified(),
                'week' => User::countSince(date('Y-m-d H:i:s', strtotime('-7 days'))),
                'circuits' => Project::countAll(),
                'messages' => $this->countTable('contact_messages'),
                'mails' => $this->countTable('email_logs'),
            ],
            'plans' => $plans,
            'planCounts' => $counts,
            'activity' => Project::recentActivity(12),
        ], 'layouts/admin');
    }

    public function clients(): void
    {
        $user = Auth::requireAdmin();
        $q = trim((string) ($_GET['q'] ?? ''));
        $plan = trim((string) ($_GET['plan'] ?? ''));
        $role = trim((string) ($_GET['role'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = User::search($q, $plan, $role, $page);
        View::render('admin/clients/index', [
            'title' => 'Clients',
            'nav' => 'clients',
            'user' => $user,
            'q' => $q,
            'planFilter' => $plan,
            'roleFilter' => $role,
            'result' => $result,
            'plans' => Plan::all(),
        ], 'layouts/admin');
    }

    public function clientCreate(): void
    {
        $user = Auth::requireAdmin();
        View::render('admin/clients/create', [
            'title' => 'Nouveau client',
            'nav' => 'clients',
            'user' => $user,
            'plans' => Plan::all(),
        ], 'layouts/admin');
    }

    public function clientStore(): void
    {
        Auth::requireAdmin();
        $first = mb_substr(trim((string) ($_POST['first_name'] ?? '')), 0, 120);
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $plan = (string) ($_POST['plan'] ?? Plan::LIBRE);
        $role = (string) ($_POST['role'] ?? 'user');

        if ($first === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flashSet('error', 'Prénom et e-mail sont nécessaires.');
            Session::flashSet('_old', ['first_name' => $first, 'email' => $email, 'plan' => $plan, 'role' => $role]);
            redirect('/admin/clients/nouveau');
        }
        if (!password_is_strong($password)) {
            Session::flashSet('error', 'Le mot de passe doit faire 12 caractères, avec majuscule, minuscule et chiffre ou symbole.');
            Session::flashSet('_old', ['first_name' => $first, 'email' => $email, 'plan' => $plan, 'role' => $role]);
            redirect('/admin/clients/nouveau');
        }
        if (User::findByEmail($email)) {
            Session::flashSet('error', 'Cette adresse est déjà utilisée.');
            Session::flashSet('_old', ['first_name' => $first, 'email' => $email, 'plan' => $plan, 'role' => $role]);
            redirect('/admin/clients/nouveau');
        }
        if (!Plan::exists($plan)) {
            $plan = Plan::LIBRE;
        }

        $created = User::create([
            'first_name' => $first,
            'email' => $email,
            'password' => $password,
            'plan' => $plan,
            'role' => $role === 'admin' ? 'admin' : 'user',
        ]);
        if (!empty($_POST['verified'])) {
            User::markVerified((int) $created['id']);
        }
        Project::log((int) $created['id'], 'Compte créé par l’administration');
        Session::flashSet('success', 'Client ' . $first . ' créé.');
        redirect('/admin/clients/' . $created['id']);
    }

    public function clientShow(string $id): void
    {
        $user = Auth::requireAdmin();
        $client = User::find((int) $id);
        if (!$client) {
            Session::flashSet('error', 'Client introuvable.');
            redirect('/admin/clients');
        }
        $clientId = (int) $client['id'];
        View::render('admin/clients/show', [
            'title' => $client['first_name'],
            'nav' => 'clients',
            'user' => $user,
            'client' => $client,
            'plans' => Plan::all(),
            'projects' => Project::allForUser($clientId),
            'members' => Access::membersForOwner($clientId),
            'activity' => Project::activity($clientId, 12),
        ], 'layouts/admin');
    }

    public function clientUpdate(string $id): void
    {
        $admin = Auth::requireAdmin();
        $client = User::find((int) $id);
        if (!$client) {
            Session::flashSet('error', 'Client introuvable.');
            redirect('/admin/clients');
        }

        $first = mb_substr(trim((string) ($_POST['first_name'] ?? '')), 0, 120);
        $email = trim((string) ($_POST['email'] ?? ''));
        $plan = (string) ($_POST['plan'] ?? $client['plan']);
        $role = (string) ($_POST['role'] ?? 'user');
        $clientId = (int) $client['id'];

        if ($first === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flashSet('error', 'Prénom et e-mail sont nécessaires.');
            redirect('/admin/clients/' . $clientId);
        }
        $existing = User::findByEmail($email);
        if ($existing && (int) $existing['id'] !== $clientId) {
            Session::flashSet('error', 'Cette adresse est déjà utilisée.');
            redirect('/admin/clients/' . $clientId);
        }
        if (!Plan::exists($plan)) {
            Session::flashSet('error', 'Ce forfait n’existe pas.');
            redirect('/admin/clients/' . $clientId);
        }
        if ($role !== 'admin' && ($client['role'] ?? '') === 'admin' && User::countAdmins() <= 1) {
            Session::flashSet('error', 'Il doit rester au moins un administrateur.');
            redirect('/admin/clients/' . $clientId);
        }
        if ($role !== 'admin' && $clientId === (int) $admin['id']) {
            Session::flashSet('error', 'Vous ne pouvez pas retirer votre propre rôle administrateur.');
            redirect('/admin/clients/' . $clientId);
        }

        User::adminUpdate($clientId, [
            'first_name' => $first,
            'email' => $email,
            'plan' => $plan,
            'role' => $role === 'admin' ? 'admin' : 'user',
        ]);
        if ($plan !== ($client['plan'] ?? '')) {
            Project::log($clientId, 'Forfait passé en ' . Plan::label($plan) . ' par l’administration');
        }
        Session::flashSet('success', 'Fiche client enregistrée.');
        redirect('/admin/clients/' . $clientId);
    }

    public function clientPassword(string $id): void
    {
        Auth::requireAdmin();
        $client = User::find((int) $id);
        if (!$client) {
            redirect('/admin/clients');
        }
        $password = (string) ($_POST['password'] ?? '');
        if (!password_is_strong($password)) {
            Session::flashSet('error', 'Le mot de passe doit faire 12 caractères, avec majuscule, minuscule et chiffre ou symbole.');
            redirect('/admin/clients/' . $client['id']);
        }
        User::updatePassword((int) $client['id'], $password);
        Auth::revokeAllTokens((int) $client['id']);
        Session::flashSet('success', 'Mot de passe mis à jour. Les sessions ouvertes ont été révoquées.');
        redirect('/admin/clients/' . $client['id']);
    }

    public function clientVerify(string $id): void
    {
        Auth::requireAdmin();
        $client = User::find((int) $id);
        if (!$client) {
            redirect('/admin/clients');
        }
        if (!empty($_POST['unverify'])) {
            User::unverify((int) $client['id']);
            Session::flashSet('success', 'Confirmation e-mail retirée.');
        } else {
            User::markVerified((int) $client['id']);
            Session::flashSet('success', 'Adresse e-mail confirmée.');
        }
        redirect('/admin/clients/' . $client['id']);
    }

    public function clientDelete(string $id): void
    {
        $admin = Auth::requireAdmin();
        $client = User::find((int) $id);
        if (!$client) {
            redirect('/admin/clients');
        }
        if ((int) $client['id'] === (int) $admin['id']) {
            Session::flashSet('error', 'Vous ne pouvez pas supprimer votre propre compte ici.');
            redirect('/admin/clients/' . $client['id']);
        }
        if (($client['role'] ?? '') === 'admin' && User::countAdmins() <= 1) {
            Session::flashSet('error', 'Impossible de supprimer le dernier administrateur.');
            redirect('/admin/clients/' . $client['id']);
        }
        if (mb_strtolower(trim((string) ($_POST['confirm'] ?? ''))) !== 'supprimer') {
            Session::flashSet('error', 'Saisissez « supprimer » pour confirmer.');
            redirect('/admin/clients/' . $client['id']);
        }
        User::delete((int) $client['id']);
        Session::flashSet('success', 'Compte ' . $client['email'] . ' supprimé.');
        redirect('/admin/clients');
    }

    public function plans(): void
    {
        $user = Auth::requireAdmin();
        View::render('admin/plans/index', [
            'title' => 'Forfaits',
            'nav' => 'forfaits',
            'user' => $user,
            'plans' => Plan::all(),
            'counts' => Plan::userCounts(),
        ], 'layouts/admin');
    }

    public function planCreate(): void
    {
        $user = Auth::requireAdmin();
        View::render('admin/plans/form', [
            'title' => 'Nouveau forfait',
            'nav' => 'forfaits',
            'user' => $user,
            'plan' => [
                'slug' => '',
                'label' => '',
                'blurb' => '',
                'circuits' => 1,
                'horizon' => 24,
                'members' => 0,
                'price_monthly_ht' => 0,
                'price_yearly_ht' => 0,
                'sort_order' => count(Plan::all()) + 1,
                'featured' => false,
                'cta_label' => 'Choisir',
                'cta_url' => '/creer-un-compte',
            ],
            'create' => true,
        ], 'layouts/admin');
    }

    public function planStore(): void
    {
        Auth::requireAdmin();
        $slug = slugify(trim((string) ($_POST['slug'] ?? $_POST['label'] ?? '')));
        $slug = mb_substr($slug, 0, 32);
        if ($slug === '' || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            Session::flashSet('error', 'Le slug doit être en minuscules, chiffres et tirets.');
            Session::flashSet('_old', $_POST);
            redirect('/admin/forfaits/nouveau');
        }
        if (Plan::exists($slug)) {
            Session::flashSet('error', 'Ce slug est déjà pris.');
            Session::flashSet('_old', $_POST);
            redirect('/admin/forfaits/nouveau');
        }
        if (trim((string) ($_POST['label'] ?? '')) === '') {
            Session::flashSet('error', 'Indiquez un nom de forfait.');
            Session::flashSet('_old', $_POST);
            redirect('/admin/forfaits/nouveau');
        }
        Plan::save($slug, $_POST, true);
        Session::flashSet('success', 'Forfait créé. Il apparaît sur /tarifs et dans l’espace client.');
        redirect('/admin/forfaits/' . $slug);
    }

    public function planEdit(string $slug): void
    {
        $user = Auth::requireAdmin();
        $plan = Plan::find($slug);
        if (!$plan) {
            Session::flashSet('error', 'Forfait introuvable.');
            redirect('/admin/forfaits');
        }
        View::render('admin/plans/form', [
            'title' => $plan['label'],
            'nav' => 'forfaits',
            'user' => $user,
            'plan' => $plan,
            'create' => false,
            'usersOnPlan' => Plan::userCount($slug),
        ], 'layouts/admin');
    }

    public function planUpdate(string $slug): void
    {
        Auth::requireAdmin();
        if (!Plan::exists($slug)) {
            Session::flashSet('error', 'Forfait introuvable.');
            redirect('/admin/forfaits');
        }
        if (trim((string) ($_POST['label'] ?? '')) === '') {
            Session::flashSet('error', 'Indiquez un nom de forfait.');
            redirect('/admin/forfaits/' . $slug);
        }
        Plan::save($slug, $_POST, false);
        Session::flashSet('success', 'Forfait mis à jour.');
        redirect('/admin/forfaits/' . $slug);
    }

    public function planDelete(string $slug): void
    {
        Auth::requireAdmin();
        if (!Plan::exists($slug)) {
            redirect('/admin/forfaits');
        }
        if (count(Plan::all()) <= 1) {
            Session::flashSet('error', 'Il doit rester au moins un forfait.');
            redirect('/admin/forfaits/' . $slug);
        }
        $n = Plan::userCount($slug);
        if ($n > 0) {
            Session::flashSet('error', 'Impossible de supprimer : ' . $n . ' client' . ($n > 1 ? 's sont' : ' est') . ' encore sur ce forfait.');
            redirect('/admin/forfaits/' . $slug);
        }
        Plan::delete($slug);
        Session::flashSet('success', 'Forfait supprimé.');
        redirect('/admin/forfaits');
    }

    public function envForm(): void
    {
        $user = Auth::requireAdmin();
        View::render('admin/env', [
            'title' => 'Environnement',
            'nav' => 'environnement',
            'user' => $user,
            'values' => EnvFile::read(),
            'groups' => EnvFile::groups(),
            'writable' => EnvFile::writable(),
            'hasKey' => trim((string) (EnvFile::read()['APP_KEY'] ?? '')) !== '',
        ], 'layouts/admin');
    }

    public function envSave(): void
    {
        Auth::requireAdmin();
        if (!EnvFile::writable()) {
            Session::flashSet('error', 'Le fichier .env n’est pas inscriptible.');
            redirect('/admin/environnement');
        }

        $current = EnvFile::read();
        $updates = [
            'APP_NAME' => trim((string) ($_POST['APP_NAME'] ?? $current['APP_NAME'] ?? 'repartio')),
            'APP_ENV' => in_array($_POST['APP_ENV'] ?? '', ['local', 'production'], true) ? (string) $_POST['APP_ENV'] : 'local',
            'APP_DEBUG' => isset($_POST['APP_DEBUG']) ? '1' : '0',
            'APP_URL' => rtrim(trim((string) ($_POST['APP_URL'] ?? '')), '/'),
            'DB_DRIVER' => $current['DB_DRIVER'] ?? 'mysql',
            'DB_HOST' => trim((string) ($_POST['DB_HOST'] ?? '127.0.0.1')),
            'DB_PORT' => trim((string) ($_POST['DB_PORT'] ?? '3306')),
            'DB_NAME' => trim((string) ($_POST['DB_NAME'] ?? 'repartio')),
            'DB_USER' => trim((string) ($_POST['DB_USER'] ?? 'root')),
            'DB_PASS' => (string) ($_POST['DB_PASS'] ?? ''),
            'MAIL_DRIVER' => in_array($_POST['MAIL_DRIVER'] ?? '', ['file', 'smtp', 'mail'], true) ? (string) $_POST['MAIL_DRIVER'] : 'file',
            'MAIL_HOST' => trim((string) ($_POST['MAIL_HOST'] ?? '')),
            'MAIL_PORT' => trim((string) ($_POST['MAIL_PORT'] ?? '587')),
            'MAIL_USER' => trim((string) ($_POST['MAIL_USER'] ?? '')),
            'MAIL_PASS' => (string) ($_POST['MAIL_PASS'] ?? ''),
            'MAIL_ENCRYPTION' => in_array($_POST['MAIL_ENCRYPTION'] ?? '', ['starttls', 'tls', 'ssl', 'none'], true)
                ? (string) $_POST['MAIL_ENCRYPTION']
                : 'starttls',
            'MAIL_FROM' => trim((string) ($_POST['MAIL_FROM'] ?? '')),
            'MAIL_FROM_NAME' => trim((string) ($_POST['MAIL_FROM_NAME'] ?? 'repartio')),
            'MAIL_ADMIN' => trim((string) ($_POST['MAIL_ADMIN'] ?? '')),
            'REINVENT_API_URL' => rtrim(trim((string) ($_POST['REINVENT_API_URL'] ?? 'https://secure.reinvent.fr')), '/'),
            'REINVENT_PLATFORM' => trim((string) ($_POST['REINVENT_PLATFORM'] ?? 'repartio')) ?: 'repartio',
            'REINVENT_API_KEY' => (string) ($_POST['REINVENT_API_KEY'] ?? ''),
            'REINVENT_WEBHOOK_SECRET' => (string) ($_POST['REINVENT_WEBHOOK_SECRET'] ?? ''),
        ];

        $merged = $current;
        foreach ($updates as $key => $value) {
            if (in_array($key, EnvFile::SECRETS, true) && $value === '') {
                continue;
            }
            $merged[$key] = $value;
        }

        try {
            EnvFile::testDatabase($merged);
            EnvFile::write($updates, ['DB_PASS', 'MAIL_PASS', 'APP_KEY', 'REINVENT_API_KEY', 'REINVENT_WEBHOOK_SECRET']);
            EnvFile::reload();
        } catch (Throwable $e) {
            Session::flashSet('error', $e->getMessage());
            redirect('/admin/environnement');
        }

        Session::flashSet('success', 'Fichier .env enregistré. La connexion MySQL a été vérifiée.');
        redirect('/admin/environnement');
    }

    public function envRegenerateKey(): void
    {
        Auth::requireAdmin();
        if (!EnvFile::writable()) {
            Session::flashSet('error', 'Le fichier .env n’est pas inscriptible.');
            redirect('/admin/environnement');
        }
        try {
            EnvFile::write(['APP_KEY' => 'base64:' . bin2hex(random_bytes(16))]);
            EnvFile::reload();
        } catch (Throwable $e) {
            Session::flashSet('error', $e->getMessage());
            redirect('/admin/environnement');
        }
        Session::flashSet('success', 'Nouvelle clé d’application générée.');
        redirect('/admin/environnement');
    }

    public function envTestMail(): void
    {
        $admin = Auth::requireAdmin();
        $to = trim((string) ($admin['email'] ?? ''));
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Session::flashSet('error', 'Votre compte n’a pas d’adresse e-mail valide.');
            redirect('/admin/environnement');
        }
        try {
            (new Mailer())->send($to, 'Test de courrier — repartio', 'welcome', [
                'first_name' => $admin['first_name'],
                'verify_url' => app_url('/app'),
            ]);
        } catch (Throwable $e) {
            Session::flashSet('error', 'Envoi impossible : ' . $e->getMessage());
            redirect('/admin/environnement');
        }
        Session::flashSet('success', 'E-mail de test envoyé à ' . $to . '.');
        redirect('/admin/environnement');
    }

    public function messages(): void
    {
        $user = Auth::requireAdmin();
        $messages = [];
        try {
            $messages = Database::fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 200');
        } catch (Throwable) {
            $messages = [];
        }
        View::render('admin/messages', [
            'title' => 'Messages',
            'nav' => 'messages',
            'user' => $user,
            'messages' => $messages,
        ], 'layouts/admin');
    }

    public function messageDelete(string $id): void
    {
        Auth::requireAdmin();
        Database::query('DELETE FROM contact_messages WHERE id = ?', [(int) $id]);
        Session::flashSet('success', 'Message supprimé.');
        redirect('/admin/messages');
    }

    public function emails(): void
    {
        $user = Auth::requireAdmin();
        $logs = [];
        try {
            $logs = Database::fetchAll('SELECT * FROM email_logs ORDER BY created_at DESC LIMIT 200');
        } catch (Throwable) {
            $logs = [];
        }
        View::render('admin/emails', [
            'title' => 'E-mails envoyés',
            'nav' => 'emails',
            'user' => $user,
            'logs' => $logs,
        ], 'layouts/admin');
    }

    private function countTable(string $table): int
    {
        try {
            $allowed = ['contact_messages', 'email_logs'];
            if (!in_array($table, $allowed, true)) {
                return 0;
            }
            $row = Database::fetch('SELECT COUNT(*) AS n FROM ' . $table);
            return (int) ($row['n'] ?? 0);
        } catch (Throwable) {
            return 0;
        }
    }
}
