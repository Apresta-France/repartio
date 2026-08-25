<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Models\Access;
use App\Models\Plan;
use App\Models\Project;
use App\Models\User;

class AppController
{
    public function dashboard(): void
    {
        $user = Auth::requireUser();
        $projects = Access::allProjectsForUser((int) $user['id']);
        $current = $projects[0] ?? null;
        View::render('app/dashboard', [
            'title' => 'Tableau de bord',
            'nav' => 'dashboard',
            'user' => $user,
            'projects' => $projects,
            'current' => $current,
            'activity' => Project::activity((int) $user['id']),
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], 'layouts/app');
    }

    public function billing(): void
    {
        $user = Auth::requireUser();
        $reason = (string) ($_GET['raison'] ?? '');
        if (!in_array($reason, ['circuits', 'invitations'], true)) {
            $reason = Project::atPlanLimit($user) ? 'circuits' : '';
        }
        View::render('app/billing', [
            'title' => 'Changer de forfait',
            'nav' => 'forfait',
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
            'memberCount' => Access::countForOwner((int) $user['id']),
            'reason' => $reason,
        ], 'layouts/app');
    }

    public function changePlan(): void
    {
        $user = Auth::requireUser();
        $slug = (string) ($_POST['plan'] ?? '');
        $reason = (string) ($_POST['reason'] ?? '');
        if (!Plan::exists($slug)) {
            Session::flashSet('error', 'Ce forfait n’existe pas.');
            redirect('/app/forfait');
        }
        if (Plan::slug($user) === $slug) {
            Session::flashSet('success', 'Vous êtes déjà sur le forfait ' . Plan::label($slug) . '.');
            redirect('/app/forfait');
        }

        User::updatePlan((int) $user['id'], $slug);
        Project::log((int) $user['id'], 'Forfait passé en ' . Plan::label($slug));
        Session::flashSet('success', 'Forfait ' . Plan::label($slug) . ' activé. ' . Plan::blurb($slug));

        if ($reason === 'invitations') {
            redirect('/app/acces');
        }
        redirect('/app/circuits');
    }

    public function profile(): void
    {
        $user = Auth::requireUser();
        View::render('app/profile', [
            'title' => 'Mon profil',
            'nav' => 'profil',
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], 'layouts/app');
    }

    public function updateProfile(): void
    {
        $user = Auth::requireUser();
        $first = mb_substr(trim((string) ($_POST['first_name'] ?? '')), 0, 120);
        $email = trim((string) ($_POST['email'] ?? ''));
        if ($first === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flashSet('error', 'Prénom et e-mail sont nécessaires.');
            redirect('/app/profil');
        }
        $existing = User::findByEmail($email);
        if ($existing && (int) $existing['id'] !== (int) $user['id']) {
            Session::flashSet('error', 'Cette adresse est déjà utilisée.');
            redirect('/app/profil');
        }
        User::updateProfile((int) $user['id'], $first, $email);
        Session::flashSet('success', 'Profil mis à jour.');
        redirect('/app/profil');
    }

    public function settings(): void
    {
        $user = Auth::requireUser();
        View::render('app/settings', [
            'title' => 'Réglages',
            'nav' => 'reglages',
            'user' => $user,
            'recents' => Access::recentsForUser((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], 'layouts/app');
    }

    public function deleteAccount(): void
    {
        $user = Auth::requireUser();
        $confirm = trim((string) ($_POST['confirm'] ?? ''));
        if (mb_strtolower($confirm) !== 'supprimer') {
            Session::flashSet('error', 'Saisissez « supprimer » pour confirmer.');
            redirect('/app/reglages');
        }
        Auth::logout();
        User::delete((int) $user['id']);
        Session::flashSet('success', 'Compte et circuits supprimés.');
        redirect('/');
    }
}
