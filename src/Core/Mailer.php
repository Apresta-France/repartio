<?php

declare(strict_types=1);

namespace App\Core;

class Mailer
{
    public function send(string $to, string $subject, string $template, array $data = []): bool
    {
        $html = View::html('emails/' . $template, $data, 'emails/layout');
        $text = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html));

        $this->log($to, $subject, $template);

        $driver = (string) Config::get('mail.driver', 'file');
        return match ($driver) {
            'smtp' => $this->sendSmtp($to, $subject, $html, $text),
            'mail' => $this->sendPhpMail($to, $subject, $html),
            default => $this->sendFile($to, $subject, $html),
        };
    }

    private function log(string $to, string $subject, string $template): void
    {
        try {
            Database::query(
                'INSERT INTO email_logs (recipient, subject, template, created_at) VALUES (?, ?, ?, NOW())',
                [$to, $subject, $template]
            );
        } catch (\Throwable) {
            // table may not exist yet during install
        }
    }

    private function sendFile(string $to, string $subject, string $html): bool
    {
        $dir = BASE_PATH . '/storage/mail';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $name = date('Ymd-His') . '-' . preg_replace('/[^a-z0-9]+/i', '-', $to) . '.html';
        $body = "<!-- To: {$to} | Subject: {$subject} -->\n" . $html;
        return file_put_contents($dir . '/' . $name, $body) !== false;
    }

    private function sendPhpMail(string $to, string $subject, string $html): bool
    {
        $from = (string) Config::get('mail.from');
        $fromName = (string) Config::get('mail.from_name');
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . sprintf('"%s" <%s>', $this->encode($fromName), $from),
            'Reply-To: ' . $from,
        ];
        return mail($to, $this->encode($subject), $html, implode("\r\n", $headers));
    }

    private function sendSmtp(string $to, string $subject, string $html, string $text): bool
    {
        $host = (string) Config::get('mail.host');
        $port = (int) Config::get('mail.port', 587);
        $user = (string) Config::get('mail.user');
        $pass = (string) Config::get('mail.pass');
        $from = (string) Config::get('mail.from');
        $fromName = (string) Config::get('mail.from_name');

        $fp = @stream_socket_client('tcp://' . $host . ':' . $port, $errno, $errstr, 20, STREAM_CLIENT_CONNECT);
        if (!$fp) {
            throw new \RuntimeException('SMTP inaccessible : ' . $errstr);
        }
        stream_set_timeout($fp, 20);

        $this->expect($fp, [220]);
        $this->cmd($fp, 'EHLO repartio.fr', [250]);
        $this->cmd($fp, 'STARTTLS', [220]);
        $crypto = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
        if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
            $crypto |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
        }
        if (!stream_socket_enable_crypto($fp, true, $crypto)) {
            throw new \RuntimeException('Échec de la négociation STARTTLS.');
        }
        $this->cmd($fp, 'EHLO repartio.fr', [250]);
        if ($user !== '') {
            $this->cmd($fp, 'AUTH LOGIN', [334]);
            $this->cmd($fp, base64_encode($user), [334]);
            $this->cmd($fp, base64_encode($pass), [235]);
        }

        $this->cmd($fp, 'MAIL FROM:<' . $from . '>', [250]);
        $this->cmd($fp, 'RCPT TO:<' . $to . '>', [250, 251]);
        $this->cmd($fp, 'DATA', [354]);

        $boundary = 'repartio-' . bin2hex(random_bytes(8));
        $headers = [
            'Date: ' . date('r'),
            'From: "' . $this->encode($fromName) . '" <' . $from . '>',
            'To: <' . $to . '>',
            'Subject: ' . $this->encode($subject),
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        ];
        $body = implode("\r\n", $headers) . "\r\n\r\n";
        $body .= '--' . $boundary . "\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n" . $text . "\r\n\r\n";
        $body .= '--' . $boundary . "\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n" . $html . "\r\n\r\n";
        $body .= '--' . $boundary . "--\r\n.";
        $this->cmd($fp, $body, [250]);
        $this->cmd($fp, 'QUIT', [221, 250]);
        fclose($fp);
        return true;
    }

    private function cmd($fp, string $line, array $ok): void
    {
        fwrite($fp, $line . "\r\n");
        $this->expect($fp, $ok);
    }

    private function expect($fp, array $ok): void
    {
        $response = '';
        while (($line = fgets($fp, 515)) !== false) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $ok, true)) {
            throw new \RuntimeException('Réponse SMTP inattendue : ' . trim($response));
        }
    }

    private function encode(string $value): string
    {
        return '=?UTF-8?B?' . base64_encode($value) . '?=';
    }
}
