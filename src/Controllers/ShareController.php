<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Mailer;
use App\Core\Session;
use App\Core\View;
use App\Models\Project;
use App\Models\Share;
use Throwable;

class ShareController
{
    public function show(string $id): void
    {
        [$user, $project, $share, $sends] = $this->context($id);
        View::render('app/share', [
            'title' => 'Partager « ' . $project['name'] . ' »',
            'nav' => 'projets',
            'user' => $user,
            'project' => $project,
            'share' => $share,
            'sends' => $sends,
            'recents' => Project::recents((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
            'suggestedTitle' => $share['title'] ?? $project['name'],
        ], 'layouts/app');
    }

    public function store(string $id): void
    {
        [$user, $project, $share] = $this->context($id);
        $title = trim((string) ($_POST['title'] ?? '')) ?: $project['name'];
        $title = mb_substr($title, 0, 180);

        if ($share) {
            Share::update((int) $share['id'], (int) $user['id'], $title, true);
            $share = Share::findForProject((int) $project['id'], (int) $user['id']);
        } else {
            $share = Share::create((int) $project['id'], (int) $user['id'], $title);
        }

        Project::log((int) $user['id'], 'Lien de partage mis à jour', (int) $project['id']);

        $emails = Share::parseEmails((string) ($_POST['emails'] ?? ''));
        $note = trim((string) ($_POST['note'] ?? ''));
        $sent = 0;
        $failed = 0;

        if ($emails) {
            if (count($emails) > 20) {
                $emails = array_slice($emails, 0, 20);
            }
            $mailer = new Mailer();
            $previewUrl = Share::publicUrl($share);
            foreach ($emails as $email) {
                try {
                    $mailer->send($email, $user['first_name'] . ' vous partage un circuit', 'circuit-share', [
                        'owner_name' => $user['first_name'],
                        'title' => $title,
                        'preview_url' => $previewUrl,
                        'note' => $note,
                    ]);
                    Share::logSend((int) $share['id'], $email);
                    $sent++;
                } catch (Throwable) {
                    $failed++;
                }
            }
        }

        if ($sent > 0 && $failed === 0) {
            Session::flashSet('success', $sent === 1
                ? 'Lien enregistré et invitation envoyée.'
                : 'Lien enregistré et ' . $sent . ' invitations envoyées.');
        } elseif ($sent > 0) {
            Session::flashSet('success', $sent . ' invitation(s) envoyée(s), ' . $failed . ' échec(s).');
        } elseif ($failed > 0) {
            Session::flashSet('error', 'Le lien est enregistré, mais l’envoi des e-mails a échoué.');
        } else {
            Session::flashSet('success', 'Lien d’aperçu public enregistré.');
        }

        $this->backTo((int) $project['id']);
    }

    public function revoke(string $id): void
    {
        [$user, $project, $share] = $this->context($id);
        if ($share) {
            Share::setEnabled((int) $share['id'], (int) $user['id'], false);
            Project::log((int) $user['id'], 'Lien de partage révoqué', (int) $project['id']);
            Session::flashSet('success', 'Le lien public n’est plus accessible.');
        }
        $this->backTo((int) $project['id']);
    }

    public function restore(string $id): void
    {
        [$user, $project, $share] = $this->context($id);
        if ($share) {
            Share::setEnabled((int) $share['id'], (int) $user['id'], true);
            Project::log((int) $user['id'], 'Lien de partage réactivé', (int) $project['id']);
            Session::flashSet('success', 'Le lien public est de nouveau actif.');
        }
        $this->backTo((int) $project['id']);
    }

    public function preview(string $slug): void
    {
        $share = Share::findPublic($slug);
        if (!$share) {
            http_response_code(404);
            View::render('public/share-unavailable', [
                'title' => 'Lien indisponible',
            ], 'layouts/site');
            return;
        }

        $payload = json_decode((string) $share['payload'], true) ?: Project::emptyPayload();
        View::render('public/circuit-preview', [
            'title' => $share['title'],
            'share' => $share,
            'payload' => $payload,
            'builder' => true,
        ], 'layouts/preview');
    }

    /**
     * @return array{0: array, 1: array, 2: ?array, 3?: array}
     */
    private function context(string $id): array
    {
        $user = Auth::requireUser();
        $project = Project::findForUser((int) $id, (int) $user['id']);
        if (!$project) {
            Session::flashSet('error', 'Circuit introuvable.');
            redirect('/app/circuits');
        }
        $share = Share::findForProject((int) $project['id'], (int) $user['id']);
        $sends = $share ? Share::sendsForShare((int) $share['id']) : [];
        return [$user, $project, $share, $sends];
    }

    private function backTo(int $projectId): void
    {
        $target = (string) ($_POST['return_to'] ?? '');
        if ($target === 'builder') {
            redirect('/app/circuits/' . $projectId);
        }
        redirect('/app/circuits/' . $projectId . '/partage');
    }
}
