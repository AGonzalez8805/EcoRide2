<?php

namespace App\Controller;

use App\Db\Mysql;
use App\Db\MongoDb;
use App\Repository\AvisRepository;
use App\Models\Avis;
use App\Repository\UserRepository;

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['typeUtilisateur']) || $_SESSION['typeUtilisateur'] !== 'passager') {
            // L'utilisateur n'est pas connecté → redirige vers login
            header('Location: /?controller=auth&action=login');
            exit();
        }

        // Récupération des chauffeurs
        $pdo = Mysql::getInstance()->getPDO();
        $userRepo = new UserRepository($pdo);
        $chauffeurs = $userRepo->findChauffeurs();

        // Rendu de la vue avec la liste des chauffeurs
        $this->render('avis/avis', [
            'chauffeurs' => $chauffeurs
        ]);
    }

    public function submit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        ob_start();
        header('Content-Type: application/json');


        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['chauffeur_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chauffeur non sélectionné']);
            exit;
        }

        try {
            $db = MongoDb::getInstance()->getDatabase();
            $avisRepo = new AvisRepository($db);

            $avis = new Avis($data);
            $ok = $avisRepo->ajouter($avis, $userId);

            if ($ok) {
                echo json_encode(['success' => true, 'message' => 'Avis enregistré avec succès !']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Impossible d’enregistrer l’avis.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        exit; // <-- Important pour bloquer tout autre HTML
    }
}
