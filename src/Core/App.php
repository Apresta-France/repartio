<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AccessController;
use App\Controllers\AdminController;
use App\Controllers\AppController;
use App\Controllers\AuthController;
use App\Controllers\BillingController;
use App\Controllers\ContactController;
use App\Controllers\InstallController;
use App\Controllers\ProjectController;
use App\Controllers\ShareController;
use App\Controllers\SiteController;

class App
{
    public static function run(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'HEAD') {
            $method = 'GET';
        }
        $path = request_path();

        if (!is_installed() && !str_starts_with($path, '/install') && !str_starts_with($path, '/public/')) {
            redirect('/install');
        }

        if (is_installed() && $path !== '/sitemap.xml' && $path !== '/robots.txt') {
            try {
                (new Migrator(Database::pdo()))->run();
            } catch (\Throwable) {
                // tables already present, or DB temporarily unavailable
            }
        }

        if ($method === 'POST' && $path !== '/webhooks/reinvent' && !Csrf::check($_POST['_token'] ?? null)) {
            http_response_code(419);
            Session::flashSet('error', 'Session expirée. Merci de renvoyer le formulaire.');
            if (wants_json()) {
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'error' => 'csrf']);
                exit;
            }
            back();
        }

        if ($method !== 'GET' && $method !== 'POST') {
            http_response_code(405);
            header('Allow: GET, POST');
            exit;
        }

        $router = new Router();
        self::routes($router);
        $router->dispatch($method, $path);
    }

    private static function routes(Router $router): void
    {
        $router->get('/install', [InstallController::class, 'show']);
        $router->post('/install', [InstallController::class, 'store']);

        $router->get('/sitemap.xml', [SiteController::class, 'sitemap']);
        $router->get('/robots.txt', [SiteController::class, 'robots']);

        $router->get('/', [SiteController::class, 'home']);
        $router->get('/fonctionnement', [SiteController::class, 'fonctionnement']);
        $router->get('/circuits-types', [SiteController::class, 'circuits']);
        $router->get('/circuit-rempli', [SiteController::class, 'circuitRempli']);
        $router->get('/tarifs', [SiteController::class, 'tarifs']);
        $router->get('/vos-donnees', [SiteController::class, 'donnees']);
        $router->get('/ressources', [SiteController::class, 'ressources']);
        $router->get('/ressources/{slug}', [SiteController::class, 'article']);
        $router->get('/faq', [SiteController::class, 'faq']);
        $router->get('/contact', [ContactController::class, 'show']);
        $router->post('/contact', [ContactController::class, 'store']);
        $router->get('/mentions-legales', [SiteController::class, 'mentions']);
        $router->get('/cgu', [SiteController::class, 'cgu']);
        $router->get('/cgv', [SiteController::class, 'cgv']);
        $router->get('/confidentialite', [SiteController::class, 'confidentialite']);

        $router->get('/connexion', [AuthController::class, 'loginForm']);
        $router->post('/connexion', [AuthController::class, 'login']);
        $router->post('/connexion/lien', [AuthController::class, 'magicLink']);
        $router->get('/connexion/lien/{token}', [AuthController::class, 'magicConsume']);
        $router->get('/creer-un-compte', [AuthController::class, 'registerForm']);
        $router->post('/creer-un-compte', [AuthController::class, 'register']);
        $router->get('/verifier-email/{token}', [AuthController::class, 'verify']);
        $router->get('/mot-de-passe-oublie', [AuthController::class, 'forgotForm']);
        $router->post('/mot-de-passe-oublie', [AuthController::class, 'forgot']);
        $router->get('/reinitialiser-mot-de-passe/{token}', [AuthController::class, 'resetForm']);
        $router->post('/reinitialiser-mot-de-passe/{token}', [AuthController::class, 'reset']);
        $router->post('/deconnexion', [AuthController::class, 'logout']);

        $router->get('/app', [AppController::class, 'dashboard']);
        $router->get('/app/circuits', [ProjectController::class, 'index']);
        $router->post('/app/circuits', [ProjectController::class, 'store']);
        $router->post('/app/circuits/nouveau', [ProjectController::class, 'create']);
        $router->get('/app/circuits/nouveau', [ProjectController::class, 'createPage']);
        $router->get('/app/circuits/{id}', [ProjectController::class, 'show']);
        $router->post('/app/circuits/{id}', [ProjectController::class, 'update']);
        $router->get('/app/circuits/{id}/partage', [ShareController::class, 'show']);
        $router->post('/app/circuits/{id}/partage', [ShareController::class, 'store']);
        $router->post('/app/circuits/{id}/partage/revoquer', [ShareController::class, 'revoke']);
        $router->post('/app/circuits/{id}/partage/reactiver', [ShareController::class, 'restore']);
        $router->post('/app/circuits/{id}/dupliquer', [ProjectController::class, 'duplicate']);
        $router->post('/app/circuits/{id}/archiver', [ProjectController::class, 'archive']);
        $router->post('/app/circuits/{id}/supprimer', [ProjectController::class, 'destroy']);
        $router->get('/p/{slug}', [ShareController::class, 'preview']);
        $router->get('/app/forfait', [BillingController::class, 'show']);
        $router->post('/app/forfait', [BillingController::class, 'changePlan']);
        $router->post('/app/forfait/facturation', [BillingController::class, 'saveProfile']);
        $router->get('/app/forfait/succes', [BillingController::class, 'success']);
        $router->get('/app/forfait/echec', [BillingController::class, 'failed']);
        $router->post('/app/forfait/portail', [BillingController::class, 'portal']);
        $router->post('/app/forfait/resilier', [BillingController::class, 'cancel']);
        $router->post('/webhooks/reinvent', [BillingController::class, 'webhook']);
        $router->get('/app/acces', [AccessController::class, 'index']);
        $router->post('/app/acces', [AccessController::class, 'invite']);
        $router->post('/app/acces/{id}', [AccessController::class, 'update']);
        $router->post('/app/acces/{id}/renvoyer', [AccessController::class, 'resend']);
        $router->post('/app/acces/{id}/retirer', [AccessController::class, 'revoke']);
        $router->get('/invitation/{token}', [AccessController::class, 'showInvite']);
        $router->post('/invitation/{token}', [AccessController::class, 'acceptInvite']);
        $router->get('/app/profil', [AppController::class, 'profile']);
        $router->post('/app/profil', [AppController::class, 'updateProfile']);
        $router->get('/app/reglages', [AppController::class, 'settings']);
        $router->post('/app/reglages/supprimer', [AppController::class, 'deleteAccount']);

        $router->get('/admin', [AdminController::class, 'dashboard']);
        $router->get('/admin/clients', [AdminController::class, 'clients']);
        $router->get('/admin/clients/nouveau', [AdminController::class, 'clientCreate']);
        $router->post('/admin/clients', [AdminController::class, 'clientStore']);
        $router->get('/admin/clients/{id}', [AdminController::class, 'clientShow']);
        $router->post('/admin/clients/{id}', [AdminController::class, 'clientUpdate']);
        $router->post('/admin/clients/{id}/mot-de-passe', [AdminController::class, 'clientPassword']);
        $router->post('/admin/clients/{id}/verifier', [AdminController::class, 'clientVerify']);
        $router->post('/admin/clients/{id}/supprimer', [AdminController::class, 'clientDelete']);
        $router->get('/admin/forfaits', [AdminController::class, 'plans']);
        $router->get('/admin/forfaits/nouveau', [AdminController::class, 'planCreate']);
        $router->post('/admin/forfaits', [AdminController::class, 'planStore']);
        $router->get('/admin/forfaits/{slug}', [AdminController::class, 'planEdit']);
        $router->post('/admin/forfaits/{slug}', [AdminController::class, 'planUpdate']);
        $router->post('/admin/forfaits/{slug}/supprimer', [AdminController::class, 'planDelete']);
        $router->get('/admin/environnement', [AdminController::class, 'envForm']);
        $router->post('/admin/environnement', [AdminController::class, 'envSave']);
        $router->post('/admin/environnement/cle', [AdminController::class, 'envRegenerateKey']);
        $router->post('/admin/environnement/test-mail', [AdminController::class, 'envTestMail']);
        $router->get('/admin/messages', [AdminController::class, 'messages']);
        $router->post('/admin/messages/{id}/supprimer', [AdminController::class, 'messageDelete']);
        $router->get('/admin/emails', [AdminController::class, 'emails']);
    }
}
