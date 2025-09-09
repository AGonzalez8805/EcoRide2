<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mail;

    public function __construct(bool $debug = false)
    {
        $this->mail = new PHPMailer(true);

        // --- Debug SMTP ---
        $this->mail->SMTPDebug = $debug ? 2 : 0; // 0=off, 2=client+server
        $this->mail->Debugoutput = 'error_log';

        $this->setupSMTP();
    }

    protected function setupSMTP(): void
    {
        $mail = $this->mail;

        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? getenv('MAIL_HOST') ?? 'smtp.example.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME') ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD') ?? '';
        $mail->Port       = (int)($_ENV['MAIL_PORT'] ?? getenv('MAIL_PORT') ?? 587);

        $encryption = $_ENV['MAIL_SMTP_SECURE'] ?? getenv('MAIL_SMTP_SECURE') ?? PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = $encryption;

        // Options SSL pour éviter certains blocages
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
            ],
        ];

        // Expéditeur par défaut
        $mail->setFrom(
            $_ENV['MAIL_FROM'] ?? getenv('MAIL_FROM') ?? 'no-reply@example.com',
            $_ENV['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Support'
        );
    }

    public function sendContactMail(array $data): array
    {
        try {
            $mail = $this->mail;

            // Destinataire
            $mail->clearAddresses();
            $mail->addAddress(
                $_ENV['MAIL_TO'] ?? getenv('MAIL_TO') ?? 'support@example.com'
            );

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $data['subject'] ?? 'Sujet non défini';
            $mail->Body =
                "<p><strong>Nom:</strong> {$data['name']}</p>" .
                "<p><strong>Prénom:</strong> {$data['firstName']}</p>" .
                "<p><strong>Email:</strong> {$data['email']}</p>" .
                "<p><strong>Téléphone:</strong> {$data['phone']}</p>" .
                "<p><strong>Message:</strong><br>" . nl2br($data['message']) . "</p>";

            $mail->send();

            return ['success' => true, 'message' => 'Message envoyé avec succès !'];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Erreur SMTP : {$mail->ErrorInfo} ({$e->getMessage()})"
            ];
        }
    }
}
