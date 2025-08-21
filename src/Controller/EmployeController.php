<?php

namespace App\Controller;

use App\Db\MongoDb;
use App\Repository\AvisRepository;
use App\Repository\IncidentRepository;

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

        $stats = [
            'avisEnAttente' => count($avisEnAttente),
            'incidentsOuverts' => count($incidents),
            'avisTraites' => $avisRepo->countTraitesToday()
        ];

        $this->render('employe/dashboard', [
            'avisEnAttente' => $avisEnAttente,
            'stats' => $stats
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
}
