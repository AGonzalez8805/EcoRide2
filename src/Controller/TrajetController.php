<?php

namespace App\Controller;

use App\Models\Trajet;
use App\Repository\TrajetRepository;
use App\Repository\VehiculeRepository;

class TrajetController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }
            $action = $_GET['action'] ?? 'covoiturage';
            switch ($action) {
                case 'covoiturage':
                    $this->covoiturage();
                    break;

                case 'store':
                    $this->store();
                    break;

                case 'getApiKey':
                    $this->getApiKey();
                    break;

                case 'proxyRoute':
                    $this->proxyRoute();
                    break;

                case 'create':
                    $this->create();
                    break;

                case 'resultats':
                    $this->resultats();
                    break;

                default:
                    throw new \Exception("Action covoiturage inconnue : $action", 404);
            }
        });
    }

    public function covoiturage(): void
    {
        $trajetRepo = new TrajetRepository();
        $trajets = $trajetRepo->findAllWithDetails();

        $this->render('covoiturage/covoiturage', [
            'trajets' => $trajets
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception('Méthode non autorisée');
        }

        $prix = (float) ($_POST['prixPersonne'] ?? 0);
        $places = (int) ($_POST['nbPlace'] ?? 0);
        $depart = $_POST['lieuDepart'] ?? null;
        $destination = $_POST['lieuArrivee'] ?? null;
        $dateDepart = $_POST['dateDepart'] ?? null;
        $heureDepart = $_POST['heureDepart'] ?? null;
        $dateArrivee = $_POST['dateArrivee'] ?? null;
        $heureArrivee = $_POST['heureArrivee'] ?? null;
        $vehiculeId = $_POST['vehicule'] ?? null;

        if ($prix < 2) throw new \Exception("Le prix minimum est de 2 crédits par trajet.");
        if ($places < 1) throw new \Exception("Il doit y avoir au moins une place disponible.");
        if (!$depart || !$destination) throw new \Exception("Lieu de départ ou d'arrivée manquant.");
        if (!$dateDepart || !$heureDepart) throw new \Exception("Date/heure départ manquante.");
        if (!$dateArrivee || !$heureArrivee) throw new \Exception("Date/heure arrivée manquante.");
        if (!$vehiculeId) throw new \Exception("Le choix du véhicule est obligatoire.");
        if ($vehiculeId === 'nouveau') throw new \Exception("Veuillez ajouter un nouveau véhicule avant de créer un trajet.");

        if (!is_numeric($vehiculeId)) throw new \Exception("Identifiant de véhicule invalide.");

        $vehiculeRepo = new VehiculeRepository();
        $vehicule = $vehiculeRepo->findById((int)$vehiculeId);

        if (!$vehicule || $vehicule->getIdUtilisateurs() != $_SESSION['user_id']) {
            throw new \Exception("Véhicule invalide ou non autorisé.");
        }

        $dateHeureDepart = \DateTime::createFromFormat('Y-m-d H:i', "$dateDepart $heureDepart");
        if (!$dateHeureDepart) throw new \Exception("Format date/heure départ invalide.");

        $dateHeureArrivee = \DateTime::createFromFormat('Y-m-d H:i', "$dateArrivee $heureArrivee");
        if (!$dateHeureArrivee) throw new \Exception("Format date/heure arrivée invalide.");

        $trajet = (new Trajet())
            ->setDateDepart($dateHeureDepart->format('Y-m-d'))
            ->setHeureDepart($dateHeureDepart->format('H:i:s'))
            ->setLieuDepart($depart)
            ->setDateArrivee($dateHeureArrivee->format('Y-m-d'))
            ->setHeureArrivee($dateHeureArrivee->format('H:i:s'))
            ->setLieuArrivee($destination)
            ->setStatut('en_cours')
            ->setNbPlace($places)
            ->setPrixPersonne($prix)
            ->setIdUtilisateurs($_SESSION['user_id'])
            ->setIdVehicule((int)$vehiculeId);

        $repository = new TrajetRepository();
        $repository->save($trajet);

        // Pour AJAX, handleRoute() va gérer le JSON si on throw une exception
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function getApiKey(): void
    {
        header('Content-Type: application/json');

        $key = $_ENV['OPENROUTESERVICE_API_KEY'] ?? null;

        if (!$key) {
            echo json_encode(['error' => 'Clé API manquante']);
            return;
        }

        echo json_encode(['key' => $key]);
    }

    public function proxyRoute(): void
    {
        header("Content-Type: application/json");

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['coordinates'])) {
            echo json_encode(['error' => 'Coordonnées manquantes']);
            return;
        }

        $apiKey = $_ENV['OPENROUTESERVICE_API_KEY'] ?? null;

        if (!$apiKey) {
            echo json_encode(['error' => 'Clé API manquante']);
            return;
        }

        $ch = curl_init("https://api.openrouteservice.org/v2/directions/driving-car/geojson");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input));

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

    public function create(): void
    {
        $vehiculeRepo = new VehiculeRepository();
        $vehicules = $vehiculeRepo->findAllByUser($_SESSION['user_id']);

        $this->render('covoiturage/create', [
            'vehicules' => $vehicules
        ]);
    }

    public function resultats(): void
    {
        $depart = $_GET['depart'] ?? null;
        $arrivee = $_GET['arrivee'] ?? null;
        $date = $_GET['date'] ?? null;

        $trajetRepo = new TrajetRepository();
        $trajets = $trajetRepo->searchWithDetails($depart, $arrivee, $date);

        $this->render('covoiturage/resultats', [
            'trajets' => $trajets,
            'depart' => $depart,
            'arrivee' => $arrivee,
            'date' => $date
        ]);
    }
}
