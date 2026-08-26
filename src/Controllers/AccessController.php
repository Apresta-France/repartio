<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Mailer;
use App\Core\Session;
use App\Core\View;
use App\Models\Access;
use App\Models\Project;
use App\Models\User;
use Throwable;

class AccessController
{
    public function index(): void
    {
        $user = Auth::requireUser();
        $circuits = array_values(array_filter(
            Project::allForUser((int) $user['id']),
            static fn (array $p): bool => $p['status'] !== 'archive'
        ));
        View::render('app/access', $this->shell($user, [
            'title' => 'Accès & droits',
            'nav' => 'acces',
            'members' => Access::membersForOwner((int) $user['id']),
            'circuits' => $circuits,
            'memberCount' => Access::countForOwner((int) $user['id']),
            'memberLimit' => Access::memberLimitFor($user),
        ]), 'layouts/app');
    }

    public function invite(): void
    {
        $user = Auth::requireUser();
        $ownerId = (int) $user['id'];
        $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $assignments = Access::parseAssignments($_POST, $ownerId);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->bounceInvite('Indiquez une adresse e-mail valide.');
        }
        if ($email === mb_strtolower((string) $user['email'])) {
            $this->bounceInvite('Vous êtes déjà propriétaire de ce compte.');
        }
        if ($assignments === []) {
            $this->bounceInvite('Choisissez au moins un circuit.');
        }
        $existing = Access::findByOwnerEmail($ownerId, $email);
        $expiredExisting = $existing && Access::isPendingExpired($existing);
        if ($existing && !$expiredExisting) {
            $this->bounceInvite('Cette personne a déjà un accès. Modifiez ses droits ci-dessous.');
        }
        $memberLimit = Access::memberLimitFor($user);
        if ($memberLimit <= 0 || Access::countForOwner($ownerId) >= $memberLimit) {
            if ($expiredExisting) {
                $this->bounceInvite('Limite de personnes atteinte. Retirez un accès, ou changez de forfait, avant de renvoyer cette invitation.');
            }
            redirect(Project::planChangePath('invitations'));
        }
        if ($expiredExisting) {
            Access::revoke((int) $existing['id'], $ownerId);
        }

        $created = Access::invite($ownerId, $email, $assignments);
        $sent = $this->sendInviteMail($user, $email, $created['token'], $created['id']);
        Project::log($ownerId, 'Invitation envoyée à ' . $email);
        Session::flashSet($sent ? 'success' : 'error', $sent
            ? 'Invitation envoyée à ' . $email . '.'
            : 'L’accès est enregistré, mais l’e-mail n’a pas pu partir. Renvoyez l’invitation.');
        redirect('/app/acces');
    }

    public function update(string $id): void
    {
        $user = Auth::requireUser();
        $member = Access::findForOwner((int) $id, (int) $user['id']);
        if (!$member) {
            Session::flashSet('error', 'Personne introuvable.');
            redirect('/app/acces');
        }
        $assignments = Access::parseAssignments($_POST, (int) $user['id']);
        if ($assignments === [] && !Access::hasArchivedAssignments((int) $member['id'])) {
            Session::flashSet('error', 'Laissez au moins un circuit, ou retirez complètement l’accès.');
            redirect('/app/acces');
        }
        if (Access::assignmentsExceedMemberPlan($member, $assignments)) {
            Session::flashSet('error', 'Cette personne n’a plus d’emplacement disponible sur son forfait. Retirez un circuit, ou demandez-lui de passer à un forfait supérieur.');
            redirect('/app/acces');
        }
        Access::syncCircuits((int) $member['id'], (int) $user['id'], $assignments);
        Project::log((int) $user['id'], 'Droits mis à jour pour ' . $member['email']);
        Session::flashSet('success', 'Droits mis à jour.');
        redirect('/app/acces');
    }

    public function resend(string $id): void
    {
        $user = Auth::requireUser();
        $member = Access::findForOwner((int) $id, (int) $user['id']);
        if (!$member || $member['status'] !== 'pending') {
            Session::flashSet('error', 'Cette invitation ne peut plus être renvoyée.');
            redirect('/app/acces');
        }
        if (Access::isPendingExpired($member)
            && Access::countForOwner((int) $user['id']) >= Access::memberLimitFor($user)) {
            Session::flashSet('error', 'Limite de personnes atteinte. Retirez un accès, ou changez de forfait, avant de renvoyer cette invitation.');
            redirect('/app/acces');
        }
        $token = Access::refreshToken((int) $member['id']);
        $sent = $this->sendInviteMail($user, (string) $member['email'], $token, (int) $member['id']);
        Session::flashSet($sent ? 'success' : 'error', $sent
            ? 'Invitation renvoyée à ' . $member['email'] . '.'
            : 'L’e-mail n’a pas pu partir. Réessayez dans un instant.');
        redirect('/app/acces');
    }

    public function revoke(string $id): void
    {
        $user = Auth::requireUser();
        $member = Access::findForOwner((int) $id, (int) $user['id']);
        if ($member) {
            Access::revoke((int) $member['id'], (int) $user['id']);
            Project::log((int) $user['id'], 'Accès retiré pour ' . $member['email']);
            Session::flashSet('success', 'Accès retiré.');
        }
        redirect('/app/acces');
    }

    public function showInvite(string $token): void
    {
        $invite = Access::findByToken($token, false);
        if (!$invite) {
            Session::flashSet('error', 'Cette invitation est invalide.');
            redirect(Auth::check() ? '/app' : '/connexion');
        }
        $user = Auth::user();
        if ($user && $invite['status'] === 'active' && (int) $invite['member_id'] === (int) $user['id']) {
            Session::flashSet('success', 'Vous avez déjà accès à ces circuits.');
            redirect('/app/circuits');
        }
        if ($invite['status'] !== 'pending') {
            Session::flashSet('error', 'Cette invitation n’est plus valable.');
            redirect(Auth::check() ? '/app' : '/connexion');
        }
        if (!empty($invite['expires_at']) && strtotime((string) $invite['expires_at']) < time()) {
            Session::flashSet('error', 'Cette invitation a expiré. Demandez un nouveau lien.');
            redirect(Auth::check() ? '/app' : '/connexion');
        }

        $invite = Access::inviteWithCircuits((int) $invite['id']);
        $owner = User::find((int) $invite['owner_id']);

        if ($user && (int) $user['id'] === (int) $invite['owner_id']) {
            Session::flashSet('error', 'Vous ne pouvez pas accepter votre propre invitation.');
            redirect('/app/acces');
        }

        if ((int) ($user['id'] ?? 0) !== (int) $invite['owner_id']) {
            Session::set('invite_token', $token);
        }
        if (!$user) {
            Session::flashSet('_old', ['email' => $invite['email']]);
        }

        $needed = Access::pendingSlotCost((int) $invite['id']);
        $used = $user ? Project::activeCount((int) $user['id']) : 0;
        $limit = $user ? Project::planLimit($user) : 0;

        View::render('auth/invite', [
            'title' => 'Invitation',
            'token' => $token,
            'invite' => $invite,
            'owner' => $owner,
            'user' => $user,
            'emailMatch' => $user && mb_strtolower((string) $user['email']) === mb_strtolower((string) $invite['email']),
            'neededSlots' => $needed,
            'usedSlots' => $used,
            'planLimit' => $limit,
            'canAccept' => !$user || Project::canFit($user, $needed),
        ], 'layouts/auth');
    }

    public function acceptInvite(string $token): void
    {
        $user = Auth::requireUser();
        $invite = Access::findByToken($token);
        if (!$invite) {
            Session::flashSet('error', 'Cette invitation est invalide ou expirée.');
            redirect('/app');
        }
        if ((int) $invite['owner_id'] === (int) $user['id']) {
            Session::flashSet('error', 'Vous ne pouvez pas accepter votre propre invitation.');
            redirect('/app/acces');
        }
        if (mb_strtolower((string) $user['email']) !== mb_strtolower((string) $invite['email'])) {
            Session::flashSet('error', 'Connectez-vous avec l’adresse invitée : ' . $invite['email'] . '.');
            redirect('/invitation/' . $token);
        }
        if ($invite['status'] === 'active' && (int) $invite['member_id'] === (int) $user['id']) {
            redirect('/app/circuits');
        }
        if ($invite['status'] !== 'pending') {
            Session::flashSet('error', 'Cette invitation n’est plus valable.');
            redirect('/app');
        }

        $needed = Access::pendingSlotCost((int) $invite['id']);
        if (!Project::canFit($user, $needed)) {
            Session::flashSet('error', Project::shareLimitMessage($user, $needed));
            redirect('/invitation/' . $token);
        }

        if (!Access::accept((int) $invite['id'], (int) $user['id'])) {
            Session::flashSet('error', 'Cette invitation n’est plus valable.');
            redirect('/app');
        }
        Session::forget('invite_token');
        Project::log((int) $user['id'], 'Invitation acceptée');
        Session::flashSet('success', 'Accès accepté. Les circuits partagés apparaissent dans votre liste.');
        redirect('/app/circuits');
    }

    private function bounceInvite(string $message): void
    {
        Session::flashSet('error', $message);
        Session::flashSet('_old', [
            'email' => $_POST['email'] ?? '',
            'circuit_ids' => $_POST['circuit_ids'] ?? [],
            'permission' => $_POST['permission'] ?? 'lecture',
        ]);
        redirect('/app/acces');
    }

    private function sendInviteMail(array $owner, string $email, string $token, int $memberId): bool
    {
        $invite = Access::inviteWithCircuits($memberId);
        try {
            return (new Mailer())->send($email, $owner['first_name'] . ' vous invite sur repartio', 'access-invite', [
                'owner_name' => $owner['first_name'],
                'invite_url' => app_url('/invitation/' . $token),
                'circuits' => $invite['circuits'] ?? [],
            ]);
        } catch (Throwable) {
            return false;
        }
    }

    private function shell(array $user, array $extra = []): array
    {
        return array_merge([
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], $extra);
    }
}
