<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AccessController;
use App\Controllers\AppController;
use App\Controllers\AuthController;
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
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper((string) $_POST['_method']);
        }
        $path = request_path();

        if (!is_installed() && !str_starts_with($path, '/install') && !str_starts_with($path, '/public/')) {
            redirect('/install');
        }

        if (is_installed()) {
            try {
                (new Migrator(Database::pdo()))->run();
            } catch (\Throwable) {
                // tables already present, or DB temporarily unavailable
            }
        }

        if ($method === 'POST' && $path !== '/install' && !str_starts_with($path, '/install')) {
            if (!Csrf::check($_POST['_token'] ?? null)) {
                http_response_code(419);
                Session::flashSet('error', 'Session expirée. Merci de renvoyer le formulaire.');
                back();
            }
        }

        $router = new Router();
        self::routes($router);
        $router->dispatch($method === 'POST' || $method === 'GET' ? $method : 'POST', $path);
    }

    private static function routes(Router $router): void
    {
        $router->get('/install', [InstallController::class, 'show']);
        $router->post('/install', [InstallController::class, 'store']);

        $router->get('/', [SiteController::class, 'home']);
        $router->get('/fonctionnement', [SiteController::class, 'fonctionnement']);
        $router->get('/circuits-types', [SiteController::class, 'circuits']);
        $router->get('/circuit-rempli', [SiteController::class, 'circuitRempli']);
        $router->get('/capacites', [SiteController::class, 'capacites']);
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
        $router->get('/app/circuits/nouveau', [ProjectController::class, 'create']);
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
        $router->get('/app/forfait', [AppController::class, 'billing']);
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
    }
}
