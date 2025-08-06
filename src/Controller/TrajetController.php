<?php

namespace App\Controller;

use App\Repository\TrajetRepository;

class TrajetController extends Controller
{
    public function route(): void
    {
        $action = $_GET['action'] ?? 'covoiturage';
        switch ($action) {
            case 'covoiturage':
                $this->covoiturage();
                break;

            case 'ajouter':
                $this->ajouter();
                break;

            case 'rechercher':
                $this->rechercher();
                break;

            default:
                throw new \Exception("Action covoiturage inconnue : $action", 404);
        }
    }

    public function covoiturage(): void
    {
        $this->render('covoiturage/covoiturage');
    }

    public function ajouter(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trajet = new \App\Models\Trajet();

            $trajet->setDateDepart($_POST['dateDepart'] ?? null);
            $trajet->setHeureDepart($_POST['heureDepart'] ?? null);
            $trajet->setLieuDepart($_POST['lieuDepart'] ?? null);
            $trajet->setDateArrivee($_POST['dateArrivee'] ?? null);
            $trajet->setHeureArrivee($_POST['heureArrivee'] ?? null);
            $trajet->setLieuArrivee($_POST['lieuArrivee'] ?? null);
            $trajet->setStatut($_POST['statut'] ?? null);
            $trajet->setNbPlace(isset($_POST['nbPlace']) ? (int)$_POST['nbPlace'] : null);
            $trajet->setPrixPersonne(isset($_POST['prixPersonne']) ? (float)$_POST['prixPersonne'] : null);
            $trajet->setIdUtilisateurs(isset($_POST['idUtilisateurs']) ? (int)$_POST['idUtilisateurs'] : null);
            $trajet->setIdVehicule(isset($_POST['idVehicule']) ? (int)$_POST['idVehicule'] : null);

            $repo = new TrajetRepository();
            $repo->save($trajet);

            // Puis rediriger ou afficher un message
            header('Location: /trajet?action=covoiturage');
            exit;
        }

        // Si GET, afficher le formulaire d'ajout
        $this->render('covoiturage/ajouter');
    }

    public function rechercher(): void
    {
        $depart = $_POST['depart'] ?? '';
        $arrivee = $_POST['arrivee'] ?? '';
        $date = $_POST['date'] ?? '';

        $repo = new TrajetRepository();
        $resultats = $repo->search($depart, $arrivee, $date);

        $this->render('covoiturage/resultats', ['resultats' => $resultats]);
    }
}
