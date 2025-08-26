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
        header('Content-Type: application/json'); // ✅ force JSON
        http_response_code(200);

        // Debug
        file_put_contents("/var/www/html/debug_input.log", "sendMessage called\n", FILE_APPEND);

        $rawData = file_get_contents("php://input");
        file_put_contents("/var/www/html/debug_input.log", "Input: " . $rawData . "\n", FILE_APPEND);

        $data = json_decode($rawData, true);

        if (empty($rawData)) {
            echo json_encode([
                "success" => false,
                "message" => "php://input est vide (Apache a peut-être mangé la requête ?)",
                "headers" => getallheaders(), // <== super utile pour debug
                "method" => $_SERVER['REQUEST_METHOD']
            ]);
            return;
        }

        // Ici tu peux faire ta validation (ex: champs obligatoires)

        echo json_encode([
            "success" => true,
            "message" => "Message reçu",
            "data" => $data
        ]);
    }

    /* Valide les données du formulaire envoyées en JSON */
    public function validateForm()
    {
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents('php://input'), true);

        if ($input === null) {
            echo json_encode(["success" => false, "message" => "Données invalides"]);
            return;
        }

        $name = trim($input['name'] ?? '');
        $firstName = trim($input['firstName'] ?? '');
        $email = trim($input['email'] ?? '');
        $subject = trim($input['subject'] ?? '');
        $message = trim($input['message'] ?? '');

        if (!$name || !$firstName || !$email || !$subject || !$message) {
            echo json_encode(["success" => false, "message" => "Tous les champs sont obligatoires"]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Email invalide"]);
            return;
        }

        if (strlen($message) < 10) {
            echo json_encode(["success" => false, "message" => "Message trop court"]);
            return;
        }

        echo json_encode(["success" => true, "message" => "Formulaire valide"]);
    }
}
