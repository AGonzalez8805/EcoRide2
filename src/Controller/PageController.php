<?php

namespace App\Controller;

use App\Services\Mailer;
use App\Db\MongoDb;
use App\Repository\AvisRepository;

class PageController extends Controller
{
    /* Route les différentes actions en fonction du paramètre 'action' dans l'URL */
    public function route(): void
    {
        $this->handleRoute(function () {
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
            }
        });
    }

    /*Affiche la page d'accueil */
    protected function home()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $avisRepo = new AvisRepository(MongoDb::getInstance()->getDatabase());
        $avisValides = $avisRepo->listerValides(); // récupère tous les avis validés

        $this->render('pages/home', [
            'avisValides' => $avisValides
        ]);
    }

    /* Affiche la page des mentions légales */
    protected function mentions()
    {
        $this->render('pages/mentions', [
            'currentPage' => 'mentions' // Pour indiquer la page active dans la vue
        ]);
    }

    /* Affiche la page "À propos" */
    protected function about()
    {
        $this->render('pages/about');
    }

    /* Affiche la page contact */
    protected function contact()
    {
        $this->render('pages/contact');
    }

    /* Affiche la page FAQ */
    protected function faq()
    {
        $this->render('pages/faq');
    }

    /* Traite l'envoi d'un message via AJAX (données JSON) */
    public function sendMessage()
    {
        // Récupère les données JSON envoyées en POST
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        // Utilise le service Mailer pour envoyer le mail de contact
        $mailer = new Mailer();
        $result = $mailer->sendContactMail($data);

        // Retourne la réponse au format JSON
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    /* Valide les données du formulaire envoyées en JSON */
    public function validateForm()
    {
        header('Content-Type: application/json');
        http_response_code(200);

        // Récupère les données JSON reçues
        $input = json_decode(file_get_contents('php://input'), true);

        // Vérifie que les données existent
        if (!$input) {
            echo json_encode(["succes" => false, "message" => "Données invalides"]);
            exit;
        }

        // Récupération et nettoyage des champs
        $name = trim($input['name'] ?? '');
        $firstName = trim($input['firstName'] ?? '');
        $email = trim($input['email'] ?? '');
        $subject = trim($input['subject'] ?? '');
        $message = trim($input['message'] ?? '');

        // Vérifie que tous les champs sont remplis
        if (!$name || !$firstName || !$email || !$subject || !$message) {
            echo json_encode(["succes" => false, "message" => "Données invalides"]);
            exit;
        }

        // Vérifie que l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["succes" => false, "message" => "Email invalide"]);
            exit;
        }

        // Vérifie que le message est suffisamment long
        if (strlen($message) < 10) {
            echo json_encode(["succes" => false, "message" => "Message trop court"]);
            exit;
        }

        // Validation réussie, on renvoie un message OK
        echo json_encode(["success" => true, "message" => "Formulaire valide"]);
        exit;

        // Note : le code d'envoi de mail ici est inutile car placé après exit
        // $headers = "From: $name <$email>\r\n";
        // $headers .= "Reply-To: $email\r\n";
        // $headers .= "X-Mailer: PHP/" . phpversion();
    }
}
