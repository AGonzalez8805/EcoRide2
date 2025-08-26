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
            $mail->Host       = $_ENV['MAIL_HOST'] ?? '';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = $_ENV['MAIL_PORT'] ?? 587;

            $mail->setFrom($_ENV['MAIL_FROM'] ?? 'default@ecoride.com', $_ENV['MAIL_FROM_NAME'] ?? 'Ecoride Support');
            $mail->addAddress($_ENV['MAIL_TO'] ?? 'support@ecoride.com', 'Support Ecoride');

            $mail->isHTML(false);
            $mail->Subject = $data['subject'] ?? 'Sujet non défini';
            $mail->Body    = "Nom : " . ($data['name'] ?? '') . "\n"
                . "Prénom : " . ($data['firstName'] ?? '') . "\n"
                . "Email : " . ($data['email'] ?? '') . "\n"
                . "Téléphone : " . ($data['phone'] ?? '') . "\n"
                . "Message :\n" . ($data['message'] ?? '');

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
