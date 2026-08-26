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
        redirect(Project::path($project) . '?nouveau=1');
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
        redirect(Project::path($project));
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
        $project = $this->loadReadable($id, $user);
        if (!$project) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $this->canonicalize($id, $project);
        $projectId = (int) $project['id'];
        $archived = ($project['status'] ?? '') === 'archive';
        $canEdit = !$archived && Access::can((int) $user['id'], $projectId, 'edition');
        $canManage = !$archived && Access::can((int) $user['id'], $projectId, 'gestion');
        $share = $canManage ? Share::findForProject((int) $project['id'], (int) $project['user_id']) : null;
        $owner = User::find((int) $project['user_id']) ?? $user;
        $working = CircuitLive::workingCopy($project, $owner);
        $payload = $working['payload'];
        $project['name'] = $working['name'];
        $revision = $working['revision'];
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
            'liveAhead' => !empty($working['ahead']),
        ], 'layouts/app');
    }

    public function update(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::resolve($id);
        $projectId = (int) ($project['id'] ?? 0);
        if (!$project || ($project['status'] ?? '') === 'archive' || !Access::can((int) $user['id'], $projectId, 'edition')) {
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
            $live = CircuitLive::find($projectId);
            $from = $live
                ? json_decode((string) $live['payload'], true)
                : json_decode((string) $project['payload'], true);
            $payload = is_array($from) ? $from : Project::emptyPayload();
            if (isset($_POST['horizon'])) {
                $owner = User::find((int) $project['user_id']) ?? $user;
                $payload['horizon'] = Project::clampHorizonForUser($_POST['horizon'], $owner);
            }
            if ($live && trim((string) $live['name']) !== '' && !isset($_POST['name'])) {
                $name = mb_substr((string) $live['name'], 0, 180);
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
                redirect(Project::path($project));
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
                CircuitVersion::snapshotIfDue($projectId, (int) $user['id'], (string) $project['name'], $previous);
            } else {
                CircuitVersion::snapshot($projectId, (int) $user['id'], (string) $project['name'], $previous);
            }
        }
        $live = CircuitLive::find($projectId);
        if ($live) {
            $livePayload = json_decode((string) $live['payload'], true);
            if (is_array($livePayload) && !CircuitVersion::same((string) $live['name'], $livePayload, $name, $payload)) {
                CircuitVersion::snapshot($projectId, (int) $user['id'], (string) $live['name'], $livePayload);
            }
        }
        Project::updateById($projectId, $name, $payload);
        $revision = CircuitLive::publish($projectId, (int) $user['id'], $name, $payload);
        if (($_POST['autosave'] ?? '') !== '1') {
            Project::log((int) $user['id'], 'Circuit enregistré', $projectId);
        }

        if (wants_json()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'revision' => $revision]);
            return;
        }
        Session::flashSet('success', 'Circuit enregistré.');
        redirect(Project::path($project));
    }

    public function duplicate(string $id): void
    {
        $user = Auth::requireUser();
        $this->guardLimit($user);
        $project = Project::resolve($id);
        if (!$project || !Access::can((int) $user['id'], (int) $project['id'], 'gestion')) {
            redirect('/app/circuits');
        }
        $owner = User::find((int) $project['user_id']) ?? $user;
        $working = CircuitLive::workingCopy($project, $owner);
        $copy = Project::create((int) $user['id'], $working['name'] . ' — copie', $working['payload']);
        Project::log((int) $user['id'], 'Circuit dupliqué', (int) $copy['id']);
        redirect(Project::path($copy));
    }

    public function archive(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::resolve($id);
        $projectId = (int) ($project['id'] ?? 0);
        if ($project && Access::can((int) $user['id'], $projectId, 'gestion')) {
            $next = $project['status'] === 'archive' ? 'actif' : 'archive';
            if ($next === 'actif') {
                $owner = User::find((int) $project['user_id']);
                if ($owner && Project::atPlanLimit($owner)) {
                    Session::flashSet('error', Project::planLimitMessage($owner));
                    redirect('/app/circuits');
                }
                $blocked = Access::membersOverLimitIfActivated($projectId);
                if ($blocked !== []) {
                    $names = implode(', ', $blocked);
                    Session::flashSet('error', 'Réactivation impossible : ' . $names . ' n’a plus d’emplacement sur son forfait. Retirez cet accès, ou demandez un changement de forfait.');
                    redirect('/app/circuits');
                }
            }
            Project::setStatusById($projectId, $next);
            if ($next === 'archive') {
                $share = Share::findForProject($projectId, (int) $project['user_id']);
                if ($share) {
                    Share::setEnabled((int) $share['id'], (int) $project['user_id'], false);
                }
            }
            Project::log((int) $user['id'], $next === 'archive' ? 'Circuit archivé' : 'Circuit réactivé', $projectId);
        }
        redirect('/app/circuits');
    }

    public function leave(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::resolve($id);
        if (!$project) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $projectId = (int) $project['id'];
        if (Access::permission((int) $user['id'], $projectId) === 'proprietaire') {
            Session::flashSet('error', 'Vous êtes propriétaire de ce circuit.');
            redirect('/app/circuits');
        }
        if (!Access::leaveProject((int) $user['id'], $projectId)) {
            Session::flashSet('error', 'Impossible de quitter ce circuit.');
            redirect('/app/circuits');
        }
        Project::log((int) $user['id'], 'Accès partagé quitté', $projectId);
        Session::flashSet('success', 'Le circuit partagé a été retiré de votre compte. L’emplacement est libéré.');
        redirect('/app/circuits');
    }

    public function destroy(string $id): void
    {
        $user = Auth::requireUser();
        $project = Project::resolve($id);
        if (!$project) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $projectId = (int) $project['id'];
        if (!Access::can((int) $user['id'], $projectId, 'proprietaire')) {
            Session::flashSet('error', 'Seul le propriétaire peut supprimer un circuit.');
            redirect('/app/circuits');
        }
        Project::delete($projectId, (int) $user['id']);
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
        redirect(Project::path($project));
    }

    private function loadReadable(string $key, array $user): ?array
    {
        $project = Project::resolve($key);
        if (!$project || !Access::can((int) $user['id'], (int) $project['id'], 'lecture')) {
            return null;
        }
        return $project;
    }

    private function canonicalize(string $key, array $project): void
    {
        if (Project::isUid($key)) {
            return;
        }
        $qs = (string) ($_SERVER['QUERY_STRING'] ?? '');
        redirect(Project::path($project) . ($qs !== '' ? '?' . $qs : ''), 301);
    }

    private function guardLimit(array $user): void
    {
        if (Project::atPlanLimit($user)) {
            redirect(Project::planChangePath('circuits'));
        }
    }

}
