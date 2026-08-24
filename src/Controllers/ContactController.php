<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Mailer;
use App\Core\Session;
use App\Core\View;

class ContactController
{
    public function show(): void
    {
        View::render('site/contact', [
            'title' => 'Contact',
            'nav' => '',
            'sent' => (bool) flash('contact_sent'),
        ], 'layouts/site');
    }

    public function store(): void
    {
        $topic = trim((string) ($_POST['topic'] ?? 'autre'));
        $first = trim((string) ($_POST['first_name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $body = trim((string) ($_POST['message'] ?? ''));
        $ok = isset($_POST['privacy']);

        if ($first === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($body) < 12 || !$ok) {
            Session::flashSet('error', 'Merci de remplir les champs nécessaires et d’accepter la conservation du message.');
            Session::set('_old', compact('topic', 'first') + ['email' => $email, 'message' => $body]);
            redirect('/contact');
        }

        Database::query(
            'INSERT INTO contact_messages (topic, first_name, email, body, created_at) VALUES (?, ?, ?, ?, NOW())',
            [$topic, $first, $email, $body]
        );

        $mailer = new Mailer();
        $mailer->send((string) config('mail.admin'), 'Nouveau message — ' . $topic, 'contact', [
            'first_name' => $first,
            'email' => $email,
            'topic' => $topic,
            'body' => $body,
        ]);
        $mailer->send($email, 'Nous avons bien reçu votre message', 'contact-ack', [
            'first_name' => $first,
        ]);

        Session::flashSet('contact_sent', true);
        redirect('/contact');
    }
}
