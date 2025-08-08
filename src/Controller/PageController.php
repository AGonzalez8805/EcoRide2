<?php

namespace App\Controller;

use App\Services\Mailer;

class PageController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'home':
                        $this->home();
                        break;
                    case 'mentions':
                        $this->mentions();
                        break;
                    case 'about':
                        $this->about();
                        break;

                    case 'sendMessage':
                        $this->sendMessage();
                        break;

                    case 'contact':
                        $this->contact();
                        break;

                    case 'faq':
                        $this->faq();
                        break;

                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function home()
    {
        $this->render('pages/home');
    }

    protected function mentions()
    {
        $this->render('pages/mentions', [
            'currentPage' => 'mentions'
        ]);
    }

    protected function about()
    {
        $this->render('pages/about');
    }

    protected function contact()
    {
        $this->render('pages/contact');
    }

    protected function faq()
    {
        $this->render('pages/faq');
    }

    public function sendMessage()
    {
        // Lire les données JSON reçues
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        $mailer = new Mailer();
        $result = $mailer->sendContactMail($data);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function validateForm()
    {
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(["succes" => false, "message" => "Données invalides"]);
            exit;
        }

        $name = trim($input['name'] ?? '');
        $firstName = trim($input['firstName'] ?? '');
        $email = trim($input['email'] ?? '');
        $subject = trim($input['subject'] ?? '');
        $message = trim($input['message'] ?? '');

        if (!$name || !$firstName || !$email || !$subject || !$message) {
            echo json_encode(["succes" => false, "message" => "Données invalides"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["succes" => false, "message" => "Email invalide"]);
            exit;
        }

        if (strlen($message) < 10) {
            echo json_encode(["succes" => false, "message" => "Message trop court"]);
            exit;
        }

        // Validation réussie, on renvoie un message OK
        echo json_encode(["success" => true, "message" => "Formulaire valide"]);
        exit;

        // Envoi du message
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
    }
}
