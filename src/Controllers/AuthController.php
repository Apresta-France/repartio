<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Mailer;
use App\Core\Session;
use App\Core\View;
use App\Models\Project;
use App\Models\Token;
use App\Models\User;

class AuthController
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('/app');
        }
        View::render('auth/login', ['title' => 'Connexion'], 'layouts/auth');
    }

    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);
        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            Session::flashSet('error', 'Identifiants incorrects.');
            Session::set('_old', ['email' => $email]);
            redirect('/connexion');
        }
        Auth::login($user, $remember);
        $this->afterAuth();
    }

    public function registerForm(): void
    {
        if (Auth::check()) {
            redirect('/app');
        }
        View::render('auth/register', ['title' => 'Créer un compte'], 'layouts/auth');
    }

    public function register(): void
    {
        $first = trim((string) ($_POST['first_name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $terms = isset($_POST['terms']);

        $errors = [];
        if ($first === '') {
            $errors[] = 'Indiquez votre prénom.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse e-mail invalide.';
        }
        if (strlen($password) < 12 || !preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9\W]/', $password)) {
            $errors[] = 'Le mot de passe ne respecte pas les règles.';
        }
        if (!$terms) {
            $errors[] = 'Acceptez les conditions pour continuer.';
        }
        if (User::findByEmail($email)) {
            $errors[] = 'Un compte existe déjà pour cette adresse.';
        }
        if ($errors) {
            Session::flashSet('error', implode(' ', $errors));
            Session::set('_old', ['first_name' => $first, 'email' => $email]);
            redirect('/creer-un-compte');
        }

        $user = User::create([
            'first_name' => $first,
            'email' => $email,
            'password' => $password,
        ]);
        $token = Token::create((int) $user['id'], 'verify', 60 * 24);
        (new Mailer())->send($email, 'Bienvenue sur repartio', 'welcome', [
            'first_name' => $first,
            'verify_url' => url('/verifier-email/' . $token),
        ]);
        Auth::login($user);
        Project::log((int) $user['id'], 'Compte créé');
        Session::flashSet('success', 'Compte créé. Un e-mail de bienvenue vient de partir.');
        $this->afterAuth();
    }

    public function verify(string $token): void
    {
        $row = Token::consume($token, 'verify');
        if ($row) {
            User::markVerified((int) $row['user_id']);
            Session::flashSet('success', 'Adresse e-mail confirmée.');
        } else {
            Session::flashSet('error', 'Lien invalide ou expiré.');
        }
        redirect(Auth::check() ? '/app' : '/connexion');
    }

    public function forgotForm(): void
    {
        View::render('auth/forgot', [
            'title' => 'Mot de passe oublié',
            'sent' => (bool) flash('reset_sent'),
            'shownEmail' => (string) flash('reset_email'),
        ], 'layouts/auth');
    }

    public function forgot(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $user = filter_var($email, FILTER_VALIDATE_EMAIL) ? User::findByEmail($email) : null;
        if ($user) {
            $token = Token::create((int) $user['id'], 'reset', 60);
            (new Mailer())->send($user['email'], 'Réinitialiser votre mot de passe', 'reset', [
                'first_name' => $user['first_name'],
                'reset_url' => url('/reinitialiser-mot-de-passe/' . $token),
            ]);
        }
        Session::flashSet('reset_sent', true);
        Session::flashSet('reset_email', $email);
        redirect('/mot-de-passe-oublie');
    }

    public function resetForm(string $token): void
    {
        View::render('auth/reset', [
            'title' => 'Nouveau mot de passe',
            'token' => $token,
        ], 'layouts/auth');
    }

    public function reset(string $token): void
    {
        $password = (string) ($_POST['password'] ?? '');
        if (strlen($password) < 12) {
            Session::flashSet('error', '12 caractères minimum.');
            redirect('/reinitialiser-mot-de-passe/' . $token);
        }
        $row = Token::consume($token, 'reset');
        if (!$row) {
            Session::flashSet('error', 'Lien invalide ou expiré.');
            redirect('/mot-de-passe-oublie');
        }
        User::updatePassword((int) $row['user_id'], $password);
        Session::flashSet('success', 'Mot de passe mis à jour. Connectez-vous.');
        redirect('/connexion');
    }

    public function magicLink(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $user = filter_var($email, FILTER_VALIDATE_EMAIL) ? User::findByEmail($email) : null;
        if ($user) {
            $token = Token::create((int) $user['id'], 'magic', 30);
            (new Mailer())->send($user['email'], 'Votre lien de connexion', 'magic-link', [
                'first_name' => $user['first_name'],
                'login_url' => url('/connexion/lien/' . $token),
            ]);
        }
        Session::flashSet('success', 'Si un compte existe pour cette adresse, un lien vient d’y arriver.');
        redirect('/connexion');
    }

    public function magicConsume(string $token): void
    {
        $row = Token::consume($token, 'magic');
        if (!$row) {
            Session::flashSet('error', 'Lien de connexion invalide ou expiré.');
            redirect('/connexion');
        }
        $user = User::find((int) $row['user_id']);
        if (!$user) {
            redirect('/connexion');
        }
        Auth::login($user, true);
        $this->afterAuth();
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }

    private function afterAuth(): void
    {
        $token = trim((string) Session::get('invite_token', ''));
        if ($token !== '') {
            Session::forget('invite_token');
            redirect('/invitation/' . $token);
        }
        redirect('/app');
    }
}
