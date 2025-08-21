<?php

namespace App\Controller;

use App\Db\MongoDb;
use App\Repository\AvisRepository;

class AvisController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }
            switch ($_GET['action']) {
                case 'avis':
                    $this->avis();
                    break;
                case 'submit':
                    $this->submit();
                    break;
                default:
                    throw new \Exception("Action avis inconnue");
            }
        });
    }

    protected function avis()
    {
        $this->render('avis/avis');
    }

    public function submit(): void
    {
        header('Content-Type: application/json');

        // Récupération des données JSON envoyées par fetch()
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }

        try {
            // Connexion MongoDB via ton singleton
            $db = MongoDb::getInstance()->getDatabase();
            $avisRepo = new AvisRepository($db);

            $ok = $avisRepo->ajouter($data);

            if ($ok) {
                echo json_encode(['success' => true, 'message' => 'Avis enregistré avec succès !']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Impossible d’enregistrer l’avis.']);
                exit;
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
