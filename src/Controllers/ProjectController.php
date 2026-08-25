<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Content;
use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Models\Access;
use App\Models\CircuitLive;
use App\Models\CircuitVersion;
use App\Models\Plan;
use App\Models\Project;
use App\Models\Share;
use App\Models\User;

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
            'limit' => Project::planLimit($user),
        ], 'layouts/app');
    }

    public function createPage(): void
    {
        Auth::requireUser();
        redirect('/app/circuits');
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
        $template = trim((string) ($_POST['template'] ?? ''));
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($template !== '') {
            $this->startFromTemplate($template, $name);
        }

        $user = Auth::requireUser();
        $this->guardLimit($user);
        $name = $name !== '' ? $name : 'Nouveau circuit';
        $project = Project::create((int) $user['id'], $name, Project::emptyPayload());
        Project::log((int) $user['id'], 'Circuit créé', (int) $project['id']);
        redirect('/app/circuits/' . $project['id']);
    }

    public function resumePendingTemplate(): void
    {
        $pending = Session::get('pending_template');
        if (!is_array($pending) || trim((string) ($pending['key'] ?? '')) === '') {
            Session::forget('pending_template');
            return;
        }
        $user = Auth::user();
        if (!$user) {
            return;
        }
        if (Project::atPlanLimit($user)) {
            return;
        }
        Session::forget('pending_template');
        $this->openTemplateForUser(
            $user,
            trim((string) $pending['key']),
            trim((string) ($pending['name'] ?? ''))
        );
    }

    public function show(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if (!$project || !Access::can((int) $user['id'], (int) $id, 'lecture')) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $archived = ($project['status'] ?? '') === 'archive';
        $canEdit = !$archived && Access::can((int) $user['id'], (int) $id, 'edition');
        $canManage = Access::can((int) $user['id'], (int) $id, 'gestion');
        $share = $canManage ? Share::findForProject((int) $project['id'], (int) $project['user_id']) : null;
        $owner = User::find((int) $project['user_id']) ?? $user;
        $payload = json_decode((string) $project['payload'], true) ?: Project::emptyPayload(Plan::defaultHorizon($owner));
        $payload['horizon'] = Project::clampHorizonForUser($payload['horizon'] ?? null, $owner);
        $live = CircuitLive::find((int) $project['id']);
        if ($live) {
            $livePayload = json_decode((string) $live['payload'], true);
            if (is_array($livePayload)) {
                $payload = $livePayload;
                $payload['horizon'] = Project::clampHorizonForUser($payload['horizon'] ?? null, $owner);
                $liveName = trim((string) $live['name']);
                if ($liveName !== '') {
                    $project['name'] = $liveName;
                }
            }
            $revision = (int) $live['revision'];
        } else {
            $revision = CircuitLive::ensure((int) $project['id'], (int) $project['user_id'], (string) $project['name'], $payload);
        }
        View::render('app/builder', [
            'title' => $project['name'],
            'nav' => 'projets',
            'builder' => true,
            'user' => $user,
            'project' => $project,
            'payload' => $payload,
            'horizonMax' => Plan::horizonMax($owner),
            'horizonDefault' => Plan::defaultHorizon($owner),
            'horizonPresets' => Plan::horizonPresets($owner),
            'setup' => $canEdit && (string) ($_GET['nouveau'] ?? '') === '1' && empty($payload['nodes']),
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
            'share' => $share,
            'sends' => $share ? Share::sendsForShare((int) $share['id']) : [],
            'suggestedTitle' => $share['title'] ?? $project['name'],
            'canEdit' => $canEdit,
            'canManage' => $canManage,
            'revision' => $revision,
        ], 'layouts/app');
    }

    public function update(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if (!$project || ($project['status'] ?? '') === 'archive' || !Access::can((int) $user['id'], (int) $id, 'edition')) {
            if (wants_json()) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['ok' => false]);
                return;
            }
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $name = mb_substr(trim((string) ($_POST['name'] ?? $project['name'])) ?: $project['name'], 0, 180);
        $raw = $_POST['payload'] ?? null;
        if ($raw === null || $raw === '') {
            $payload = json_decode((string) $project['payload'], true) ?: Project::emptyPayload();
            if (isset($_POST['horizon'])) {
                $owner = User::find((int) $project['user_id']) ?? $user;
                $payload['horizon'] = Project::clampHorizonForUser($_POST['horizon'], $owner);
            }
        } else {
            $payload = is_array($raw) ? $raw : json_decode((string) $raw, true);
            if (!is_array($payload) || $payload === []) {
                if (wants_json()) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => false]);
                    return;
                }
                Session::flashSet('error', 'Impossible d’enregistrer un circuit vide ou illisible.');
                redirect('/app/circuits/' . $id);
            }
        }
        $owner = User::find((int) $project['user_id']) ?? $user;
        $payload['horizon'] = Project::clampHorizonForUser($payload['horizon'] ?? null, $owner);
        $previous = json_decode((string) $project['payload'], true);
        if (!is_array($previous)) {
            $previous = Project::emptyPayload();
        }
        if (!CircuitVersion::same((string) $project['name'], $previous, $name, $payload)) {
            if (($_POST['autosave'] ?? '') === '1') {
                CircuitVersion::snapshotIfDue((int) $id, (int) $user['id'], (string) $project['name'], $previous);
            } else {
                CircuitVersion::snapshot((int) $id, (int) $user['id'], (string) $project['name'], $previous);
            }
        }
        Project::updateById((int) $id, $name, $payload);
        $revision = CircuitLive::publish((int) $id, (int) $user['id'], $name, $payload);
        if (($_POST['autosave'] ?? '') !== '1') {
            Project::log((int) $user['id'], 'Circuit enregistré', (int) $id);
        }

        if (wants_json()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'revision' => $revision]);
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
        $copy = Project::create((int) $user['id'], $project['name'] . ' — copie', $payload);
        Project::log((int) $user['id'], 'Circuit dupliqué', (int) $copy['id']);
        redirect('/app/circuits/' . $copy['id']);
    }

    public function archive(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if ($project && Access::can((int) $user['id'], (int) $id, 'gestion')) {
            $next = $project['status'] === 'archive' ? 'actif' : 'archive';
            if ($next === 'actif') {
                $owner = User::find((int) $project['user_id']);
                if ($owner && Project::atPlanLimit($owner)) {
                    Session::flashSet('error', Project::planLimitMessage($owner));
                    redirect('/app/circuits');
                }
            }
            Project::setStatusById((int) $id, $next);
            if ($next === 'archive') {
                $share = Share::findForProject((int) $id, (int) $project['user_id']);
                if ($share) {
                    Share::setEnabled((int) $share['id'], (int) $project['user_id'], false);
                }
            }
            Project::log((int) $user['id'], $next === 'archive' ? 'Circuit archivé' : 'Circuit réactivé', (int) $id);
        }
        redirect('/app/circuits');
    }

    public function destroy(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::findById((int) $id);
        if (!$project) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        if (!Access::can((int) $user['id'], (int) $id, 'proprietaire')) {
            Session::flashSet('error', 'Seul le propriétaire peut supprimer un circuit.');
            redirect('/app/circuits');
        }
        Project::delete((int) $id, (int) $user['id']);
        Project::log((int) $user['id'], 'Circuit supprimé');
        Session::flashSet('success', 'Circuit supprimé.');
        redirect('/app/circuits');
    }

    private function startFromTemplate(string $key, string $name): void
    {
        $pack = Content::template($key);
        if ($pack === null) {
            Session::flashSet('error', 'Ce circuit type est introuvable.');
            redirect('/circuits-types');
        }

        $name = $name !== '' ? $name : (string) $pack['title'];
        $user = Auth::user();
        if (!$user) {
            Session::set('pending_template', ['key' => $key, 'name' => $name]);
            redirect('/creer-un-compte');
        }

        $this->openTemplateForUser($user, $key, $name, $pack);
    }

    private function openTemplateForUser(array $user, string $key, string $name, ?array $pack = null): void
    {
        $pack ??= Content::template($key);
        if ($pack === null) {
            Session::flashSet('error', 'Ce circuit type est introuvable.');
            redirect('/circuits-types');
        }

        $name = $name !== '' ? $name : (string) $pack['title'];
        if (Project::atPlanLimit($user)) {
            Session::set('pending_template', ['key' => $key, 'name' => $name]);
            redirect(Project::planChangePath('circuits'));
        }

        $payload = $pack['payload'] ?? Project::emptyPayload();
        $project = Project::create((int) $user['id'], $name, $payload);
        Project::log((int) $user['id'], 'Circuit créé depuis un modèle', (int) $project['id']);
        redirect('/app/circuits/' . $project['id']);
    }

    private function guardLimit(array $user): void
    {
        if (Project::atPlanLimit($user)) {
            redirect(Project::planChangePath('circuits'));
        }
    }

}
