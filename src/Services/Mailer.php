<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public function sendContactMail($data)
    {
        if (!is_array($data)) {
            return [
                'success' => false,
                'message' => 'Aucune donnée reçue ou format JSON invalide.'
            ];
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';

            // Choix du chiffrement et du port selon le provider
            if (strpos($mail->Host, 'mailtrap') !== false) {
                // Mailtrap
                $mail->Port       = (int)($_ENV['MAIL_PORT'] ?? 2525);
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                // Gmail ou autre SMTP
                $mail->Port       = (int)($_ENV['MAIL_PORT'] ?? 587);
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                // Options SSL (utile sur Heroku)
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
            }

            // Expéditeur
            $mail->setFrom(
                $_ENV['MAIL_FROM'] ?? 'default@ecoride.com',
                $_ENV['MAIL_FROM_NAME'] ?? 'Ecoride Support'
            );

            // Destinataire
            $mail->addAddress(
                $_ENV['MAIL_TO'] ?? 'support@ecoride.com',
                'Support Ecoride'
            );

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Body = "<p><strong>Nom:</strong> {$data['name']}</p>"
                . "<p><strong>Prénom:</strong> {$data['firstName']}</p>"
                . "<p><strong>Email:</strong> {$data['email']}</p>"
                . "<p><strong>Téléphone:</strong> {$data['phone']}</p>"
                . "<p><strong>Message:</strong><br>" . nl2br($data['message']) . "</p>";

            // Debug SMTP
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'error_log';

            $mail->send();

            return ['success' => true, 'message' => 'Message envoyé avec succès !'];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Le message n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}"
            ];
        }
    }
}
