<?php

namespace App\Controller;

use App\Db\Mysql;
use App\Db\MongoDb;
use App\Repository\AvisRepository;
use App\Repository\IncidentRepository;
use App\Repository\UserRepository;

class EmployeController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }

            switch ($_GET['action']) {
                case 'dashboard':
                    $this->dashboard();
                    break;

                case 'valider':
                    $this->valider($_GET['id'] ?? null);
                    break;

                case 'refuser':
                    $this->refuser($_GET['id'] ?? null);
                    break;

                case 'getAvis':
                    $this->getAvis();
                    break;

                default:
                    throw new \Exception("Action employé inconnue");
            }
        });
    }

    public function dashboard(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Récupérer les avis en attente depuis MongoDB
        $db = MongoDb::getInstance()->getDatabase();

        $avisRepo = new AvisRepository($db);
        $incidentRepo = new IncidentRepository($db);

        $avisEnAttente = $avisRepo->listerEnAttente();
        $incidents = $incidentRepo->findOuverts();

        // Récupération des chauffeurs
        $pdo = Mysql::getInstance()->getPDO();
        $userRepo = new UserRepository($pdo);
        $chauffeurs = $userRepo->findChauffeurs();

        $stats = [
            'avisEnAttente' => count($avisEnAttente),
            'incidentsOuverts' => count($incidents),
            'avisTraites' => $avisRepo->countTraitesToday()
        ];

        $employe = [
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];

        $this->render('employe/dashboard', [
            'avisEnAttente' => $avisEnAttente,
            'stats' => $stats,
            'employe' => $employe,
            'chauffeurs' => $chauffeurs
        ]);
    }

    public function valider(?string $id): void
    {
        if (!$id) {
            throw new \Exception("ID manquant pour valider l'avis");
        }

        $db = MongoDb::getInstance()->getDatabase();
        $avisRepo = new AvisRepository($db);
        $avisRepo->changerStatut($id, 'valide');

        header("Location: /?controller=employe&action=dashboard");
        exit;
    }

    public function refuser(?string $id): void
    {
        if (!$id) {
            throw new \Exception("ID manquant pour refuser l'avis");
        }

        $db = MongoDb::getInstance()->getDatabase();
        $avisRepo = new AvisRepository($db);
        $avisRepo->changerStatut($id, 'refuse');

        header("Location: /?controller=employe&action=dashboard");
        exit;
    }

    public function getAvis(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie que l'utilisateur est connecté et est employé
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode([]);
            exit;
        }

        $statut = $_GET['statut'] ?? ''; // "" / "en_attente" / "valide" / "refuse"

        $db = MongoDb::getInstance()->getDatabase();
        $avisRepo = new AvisRepository($db);

        // récupère les avis selon le statut
        if ($statut === '') {
            // Tous les statuts : en_attente + valide + refuse
            $avis = array_merge(
                $avisRepo->listerEnAttente(),
                $avisRepo->listerParStatut('valide'),
                $avisRepo->listerParStatut('refuse')
            );
        } else {
            $avis = $avisRepo->listerParStatut($statut);
        }

        $data = array_map(fn($a) => [
            'id' => $a->getId(),
            'pseudo' => $a->getPseudo(),
            'note' => $a->getNote(),
            'commentaire' => $a->getCommentaire(),
            'statut' => $a->getStatut(),
            'created_at' => $a->getDatePublication() ? $a->getDatePublication()->toDateTime()->format('d/m/Y H:i') : ''
        ], $avis);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
