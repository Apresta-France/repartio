<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Access;
use App\Models\CircuitLive;
use App\Models\CircuitVersion;
use App\Models\Project;
use App\Models\User;

class CollabController
{
    public function live(string $id): void
    {
        $user = Auth::requireUser();
        $project = $this->readable($id, (int) $user['id']);
        if (!$project) {
            $this->json(['ok' => false], 403);
            return;
        }

        $projectId = (int) $project['id'];
        $userId = (int) $user['id'];
        $clientId = $this->clientId();
        $cursorX = (float) ($_POST['cursor_x'] ?? 0);
        $cursorY = (float) ($_POST['cursor_y'] ?? 0);
        CircuitLive::heartbeat(
            $projectId,
            $userId,
            $clientId,
            $cursorX,
            $cursorY,
            ($_POST['has_cursor'] ?? '') === '1'
        );

        $canEdit = ($project['status'] ?? '') !== 'archive' && Access::can($userId, $projectId, 'edition');
        $raw = $_POST['payload'] ?? null;
        if ($canEdit && is_string($raw) && $raw !== '') {
            $payload = json_decode($raw, true);
            if (is_array($payload) && $payload !== []) {
                $owner = User::find((int) $project['user_id']) ?? $user;
                $payload['horizon'] = Project::clampHorizonForUser($payload['horizon'] ?? null, $owner);
                $name = mb_substr(trim((string) ($_POST['name'] ?? $project['name'])) ?: $project['name'], 0, 180);
                CircuitLive::publish($projectId, $userId, $name, $payload);
            }
        }

        $live = CircuitLive::find($projectId);
        if (!$live) {
            $payload = json_decode((string) $project['payload'], true) ?: Project::emptyPayload();
            $revision = CircuitLive::ensure($projectId, (int) $project['user_id'], (string) $project['name'], $payload);
            $live = CircuitLive::find($projectId);
            if (!$live) {
                $this->json([
                    'ok' => true,
                    'revision' => $revision,
                    'peers' => CircuitLive::peers($projectId, $userId, $clientId),
                ]);
                return;
            }
        }

        $since = (int) ($_POST['since'] ?? 0);
        $revision = (int) $live['revision'];
        $authorId = (int) $live['user_id'];
        $body = [
            'ok' => true,
            'revision' => $revision,
            'author_id' => $authorId,
            'author_name' => CircuitLive::authorName($authorId),
            'name' => (string) $live['name'],
            'updated_at' => (string) $live['updated_at'],
            'persisted' => !CircuitLive::isAhead($project, $live),
            'peers' => CircuitLive::peers($projectId, $userId, $clientId),
        ];
        if ($revision > $since) {
            $payload = json_decode((string) $live['payload'], true);
            if (is_array($payload)) {
                $body['payload'] = $payload;
            }
        }
        $this->json($body);
    }

    public function versions(string $id): void
    {
        $user = Auth::requireUser();
        $project = $this->readable($id, (int) $user['id']);
        if (!$project) {
            $this->json(['ok' => false], 403);
            return;
        }

        $rows = CircuitVersion::listForProject((int) $project['id']);
        $versions = [];
        foreach ($rows as $row) {
            $when = (string) $row['created_at'];
            $ts = strtotime($when);
            $author = trim((string) ($row['first_name'] ?? ''));
            $versions[] = [
                'id' => (int) $row['id'],
                'name' => (string) $row['name'],
                'author' => $author !== '' ? $author : 'Compte supprimé',
                'when' => $ts ? date('d/m/Y', $ts) . ' à ' . date('H:i', $ts) : $when,
                'ago' => time_ago($when),
            ];
        }

        $this->json([
            'ok' => true,
            'can_restore' => ($project['status'] ?? '') !== 'archive' && Access::can((int) $user['id'], (int) $project['id'], 'edition'),
            'versions' => $versions,
        ]);
    }

    public function restore(string $id): void
    {
        $user = Auth::requireUser();
        $project = $this->readable($id, (int) $user['id']);
        $projectId = (int) ($project['id'] ?? 0);
        $userId = (int) $user['id'];
        if (
            !$project
            || ($project['status'] ?? '') === 'archive'
            || !Access::can($userId, $projectId, 'edition')
        ) {
            $this->json(['ok' => false], 403);
            return;
        }

        $version = CircuitVersion::findForProject((int) ($_POST['version_id'] ?? 0), $projectId);
        if (!$version) {
            $this->json(['ok' => false, 'error' => 'missing'], 404);
            return;
        }

        $payload = json_decode((string) $version['payload'], true);
        if (!is_array($payload)) {
            $this->json(['ok' => false], 400);
            return;
        }

        $owner = User::find((int) $project['user_id']) ?? $user;
        $payload['horizon'] = Project::clampHorizonForUser($payload['horizon'] ?? null, $owner);
        $name = (string) $version['name'];

        $live = CircuitLive::find($projectId);
        $currentName = $live ? (string) $live['name'] : (string) $project['name'];
        $current = $live
            ? json_decode((string) $live['payload'], true)
            : json_decode((string) $project['payload'], true);
        if (is_array($current)) {
            CircuitVersion::snapshot($projectId, $userId, $currentName, $current);
        }
        Project::updateById($projectId, $name, $payload);
        $revision = CircuitLive::publish($projectId, $userId, $name, $payload);
        Project::log($userId, 'Version restaurée', $projectId);

        $this->json([
            'ok' => true,
            'revision' => $revision,
            'author_id' => $userId,
            'name' => $name,
            'payload' => $payload,
            'author_name' => (string) $user['first_name'],
        ]);
    }

    private function readable(string $key, int $userId): ?array
    {
        $project = Project::resolve($key);
        if (!$project || !Access::can($userId, (int) $project['id'], 'lecture')) {
            return null;
        }
        return $project;
    }

    private function clientId(): string
    {
        $raw = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($_POST['client_id'] ?? '')) ?? '';
        if ($raw === '' || strlen($raw) > 40) {
            return 'anon';
        }
        return $raw;
    }

    private function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
