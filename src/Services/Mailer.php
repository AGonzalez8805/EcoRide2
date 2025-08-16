<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public function sendContactMail($data)
    {
        $mail = new PHPMailer(true);

        try {
            // Chargement du fichier .env
            $envPath = __DIR__ . '/../../.env';
            if (!file_exists($envPath)) {
                throw new \Exception("Fichier .env introuvable.");
            }

            $config = parse_ini_file($envPath);

            // Vérification minimale
            if (!$config['MAIL_HOST'] || !$config['MAIL_USERNAME'] || !$config['MAIL_PASSWORD']) {
                throw new \Exception("Configuration mail incomplète.");
            }

            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = $config['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['MAIL_USERNAME'];
            $mail->Password = $config['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $config['MAIL_PORT'] ?? 587;

            // Paramètres d'expéditeur et destinataire
            $mail->setFrom($config['MAIL_FROM'], $config['MAIL_FROM_NAME']);
            $mail->addAddress($config['MAIL_TO'], 'Support Ecoride');

            // Contenu
            $mail->isHTML(false);
            $mail->Subject = $data['subject'] ?? 'Sujet non défini';
            $mail->Body    = "Nom : {$data['name']}\n"
                . "Prénom : {$data['firstName']}\n"
                . "Email : {$data['email']}\n"
                . "Téléphone : {$data['phone']}\n"
                . "Message :\n{$data['message']}";

            $mail->send();

            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Le message n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}"
            ];
        }
    }
}
