<?php

namespace App\Controller;

use App\Models\Participation;
use App\Repository\ParticipationRepository;
use App\Repository\TrajetRepository;

class ParticiperController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }
            switch ($_GET['action']) {
                case 'participer':
                    $this->participer();
                    break;

                case 'annuler':
                    $this->annuler();
                    break;

                default:
                    throw new \Exception("Action participer inconnue : " . $_GET['action'], 404);
            }
        });
    }

    public function participer(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception("Méthode non autorisée", 405);
        }

        $trajetId = $_POST['id_covoiturage'] ?? null;
        $nbPlace = (int)($_POST['nb_place'] ?? 1);

        if (!$trajetId) {
            throw new \Exception("Trajet non spécifié", 400);
        }

        $trajetRepo = new TrajetRepository();
        $trajet = $trajetRepo->findById((int)$trajetId);

        if (!$trajet) {
            throw new \Exception("Trajet introuvable", 404);
        }

        $participationRepo = new ParticipationRepository();
        $placesReservees = $participationRepo->countPlacesByTrajet($trajetId);
        $placesDisponibles = $trajet->getNbPlace() - $placesReservees;

        if ($nbPlace > $placesDisponibles) {
            throw new \Exception("Pas assez de places disponibles", 400);
        }

        $participation = (new Participation())
            ->setIdUtilisateurs($userId)
            ->setIdCovoiturage($trajetId)
            ->setNbPlace($nbPlace)
            ->setStatut('en_attente')
            ->setDateInscription(date('Y-m-d'));

        $participationRepo->save($participation);

        header("Location: /?controller=trajet&action=resultats&success=1");
        exit;
    }

    public function annuler(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        $trajetId = $_POST['id_covoiturage'] ?? null;
        if (!$trajetId) {
            throw new \Exception("Trajet non spécifié", 400);
        }

        $participationRepo = new ParticipationRepository();
        $participationRepo->deleteByUserAndTrajet($userId, $trajetId);

        header("Location: /?controller=user&action=dashboardPassager&success=1");
        exit;
    }
}
