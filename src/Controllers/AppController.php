<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Models\Project;
use App\Models\User;

class AppController
{
    public function dashboard(): void
    {
        $user = Auth::requireUser();
        $projects = Project::allForUser((int) $user['id']);
        $current = $projects[0] ?? null;
        View::render('app/dashboard', [
            'title' => 'Tableau de bord',
            'nav' => 'dashboard',
            'user' => $user,
            'projects' => $projects,
            'current' => $current,
            'activity' => Project::activity((int) $user['id']),
            'recents' => Project::recents((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], 'layouts/app');
    }

    public function billing(): void
    {
        $user = Auth::requireUser();
        View::render('app/billing', [
            'title' => 'Forfait & facturation',
            'nav' => 'forfait',
            'user' => $user,
            'recents' => Project::recents((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], 'layouts/app');
    }

    public function profile(): void
    {
        $user = Auth::requireUser();
        View::render('app/profile', [
            'title' => 'Mon profil',
            'nav' => 'profil',
            'user' => $user,
            'recents' => Project::recents((int) $user['id']),
            'activeCount' => Project::activeCount((int) $user['id']),
        ], 'layouts/app');
    }

    public function updateProfile(): void
    {
        $user = Auth::requireUser();
        $first = trim((string) ($_POST['first_name'] ?? ''));
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
            'recents' => Project::recents((int) $user['id']),
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
