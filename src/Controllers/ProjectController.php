<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Content;
use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Models\Access;
use App\Models\Project;
use App\Models\Share;

class ProjectController
{
    public function index(): void
    {
        $user = Auth::requireUser();
        View::render('app/projects', [
            'title' => 'Mes circuits',
            'nav' => 'projets',
            'user' => $user,
            'projects' => Access::allProjectsForUser((int) $user['id']),
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
            'limit' => Project::PLAN_LIMITS[$user['plan']] ?? 3,
        ], 'layouts/app');
    }

    public function create(): void
    {
        $user = Auth::requireUser();
        $this->guardLimit($user);
        $project = Project::create((int) $user['id'], 'Nouveau circuit', Project::emptyPayload());
        Project::log((int) $user['id'], 'Circuit créé', (int) $project['id']);
        redirect('/app/circuits/' . $project['id'] . '?nouveau=1');
    }

    public function store(): void
    {
        $user = Auth::requireUser();
        $this->guardLimit($user);
        $name = trim((string) ($_POST['name'] ?? 'Nouveau circuit')) ?: 'Nouveau circuit';
        $template = (string) ($_POST['template'] ?? '');
        $payload = Content::templatePayload($template) ?? Project::emptyPayload();
        $project = Project::create((int) $user['id'], $name, $payload);
        Project::log((int) $user['id'], 'Circuit créé depuis un modèle', (int) $project['id']);
        redirect('/app/circuits/' . $project['id']);
    }

    public function show(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if (!$project || !Access::can((int) $user['id'], (int) $id, 'lecture')) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $canEdit = Access::can((int) $user['id'], (int) $id, 'edition');
        $canManage = Access::can((int) $user['id'], (int) $id, 'gestion');
        $share = $canManage ? Share::findForProject((int) $project['id'], (int) $project['user_id']) : null;
        $payload = json_decode((string) $project['payload'], true) ?: Project::emptyPayload();
        View::render('app/builder', [
            'title' => $project['name'],
            'nav' => 'projets',
            'builder' => true,
            'user' => $user,
            'project' => $project,
            'payload' => $payload,
            'setup' => $canEdit && (string) ($_GET['nouveau'] ?? '') === '1' && empty($payload['nodes']),
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
            'share' => $share,
            'sends' => $share ? Share::sendsForShare((int) $share['id']) : [],
            'suggestedTitle' => $share['title'] ?? $project['name'],
            'canEdit' => $canEdit,
            'canManage' => $canManage,
        ], 'layouts/app');
    }

    public function update(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if (!$project || !Access::can((int) $user['id'], (int) $id, 'edition')) {
            http_response_code(404);
            echo json_encode(['ok' => false]);
            return;
        }
        $name = trim((string) ($_POST['name'] ?? $project['name'])) ?: $project['name'];
        $raw = $_POST['payload'] ?? '';
        $payload = is_array($raw) ? $raw : (json_decode((string) $raw, true) ?: []);
        if (!$payload) {
            Session::flashSet('error', 'Impossible d’enregistrer un circuit vide ou illisible.');
            redirect('/app/circuits/' . $id);
        }
        Project::updateById((int) $id, $name, $payload);
        Project::log((int) $user['id'], 'Circuit enregistré', (int) $id);

        if (($this->wantsJson())) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            return;
        }
        Session::flashSet('success', 'Circuit enregistré.');
        redirect('/app/circuits/' . $id);
    }

    public function duplicate(string $id): void
    {
        $user = Auth::requireUser();
        $this->guardLimit($user);
        $project = Project::findById((int) $id);
        if (!$project || !Access::can((int) $user['id'], (int) $id, 'gestion')) {
            redirect('/app/circuits');
        }
        $payload = json_decode((string) $project['payload'], true) ?: Project::emptyPayload();
        $copy = Project::create((int) $user['id'], $project['name'] . ' — copie', $payload, 'scenario');
        Project::log((int) $user['id'], 'Circuit dupliqué', (int) $copy['id']);
        redirect('/app/circuits/' . $copy['id']);
    }

    public function archive(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if ($project && Access::can((int) $user['id'], (int) $id, 'gestion')) {
            $next = $project['status'] === 'archive' ? 'actif' : 'archive';
            Project::setStatusById((int) $id, $next);
            Project::log((int) $user['id'], $next === 'archive' ? 'Circuit archivé' : 'Circuit réactivé', (int) $id);
        }
        redirect('/app/circuits');
    }

    public function destroy(string $id): void
    {
        $user = Auth::requireUser();
        if (!Access::can((int) $user['id'], (int) $id, 'proprietaire')) {
            Session::flashSet('error', 'Seul le propriétaire peut supprimer un circuit.');
            redirect('/app/circuits');
        }
        Project::delete((int) $id, (int) $user['id']);
        Project::log((int) $user['id'], 'Circuit supprimé');
        Session::flashSet('success', 'Circuit supprimé.');
        redirect('/app/circuits');
    }

    private function guardLimit(array $user): void
    {
        $limit = Project::PLAN_LIMITS[$user['plan']] ?? 3;
        if (Project::activeCount((int) $user['id']) >= $limit) {
            Session::flashSet('error', 'Limite du plan Libre atteinte (3 circuits). Passez en Complet pour continuer.');
            redirect('/app/forfait');
        }
    }

    private function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return str_contains($accept, 'application/json') || strtolower($xhr) === 'xmlhttprequest';
    }
}
