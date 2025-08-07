<?php

namespace App\Controller;


use App\Repository\TrajetRepository;
use App\Models\Trajet;
use App\Repository\VehiculeRepository;

class ChauffeurController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'create':
                        $this->create();
                        break;

                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            // Gestion d'erreurs spécifique pour les requêtes AJAX
            if (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            } else {
                // Affichage d'une vue d'erreur standard
                $this->render('errors/default', [
                    'errors' => $e->getMessage()
                ]);
            }
        }
    }

    public function create(): void
    {
        $vehiculeRepo = new VehiculeRepository();
        $vehicules = $vehiculeRepo->findAllByUser($_SESSION['user_id']);

        $this->render('covoiturage/create', [
            'vehicules' => $vehicules
        ]);
    }
}
