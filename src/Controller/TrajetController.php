<?php

namespace App\Controller;

use App\Models\Trajet;
use App\Repository\TrajetRepository;
use App\Repository\VehiculeRepository;

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

            default:
                throw new \Exception("Action covoiturage inconnue : $action", 404);
        }
    }

    public function covoiturage(): void
    {
        $trajetRepo = new TrajetRepository();
        $trajets = $trajetRepo->findAllWithDetails();

        $this->render('covoiturage/covoiturage', [
            'trajets' => $trajets
        ]);
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
            header('Location: /?controller=trajet&action=ajouter');
            exit;
        }

        // Si GET, afficher le formulaire d'ajout
        $this->render('covoiturage/ajouter');
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                // Récupération des données avec noms de champs HTML
                $prix = (float) ($_POST['prixPersonne'] ?? 0);
                $places = (int) ($_POST['nbPlace'] ?? 0);
                $depart = $_POST['lieuDepart'] ?? null;
                $destination = $_POST['lieuArrivee'] ?? null;
                $dateDepart = $_POST['dateDepart'] ?? null;
                $heureDepart = $_POST['heureDepart'] ?? null;
                $dateArrivee = $_POST['dateArrivee'] ?? null;
                $heureArrivee = $_POST['heureArrivee'] ?? null;
                $vehiculeId = $_POST['vehicule'] ?? null;

                // Validation basique
                if ($prix < 2) {
                    throw new \Exception("Le prix minimum est de 2 crédits par trajet.");
                }
                if ($places < 1) {
                    throw new \Exception("Il doit y avoir au moins une place disponible.");
                }
                if (!$dateDepart || !$heureDepart) {
                    throw new \Exception("La date et l'heure de départ sont manquantes.");
                }
                if (!$dateArrivee || !$heureArrivee) {
                    throw new \Exception("La date et l'heure d'arrivée sont manquantes.");
                }
                if (!$depart) {
                    throw new \Exception("Le lieu de départ est manquant.");
                }
                if (!$destination) {
                    throw new \Exception("Le lieu d'arrivée est manquant.");
                }
                if (!$vehiculeId) throw new \Exception("Le choix du véhicule est obligatoire.");

                if ($vehiculeId === 'nouveau') {
                    echo json_encode([
                        'success' => false,
                        'message' => "Veuillez d'abord ajouter un nouveau véhicule avant de créer un trajet."
                    ]);
                    return;
                }

                if (!is_numeric($vehiculeId)) {
                    echo json_encode([
                        'success' => false,
                        'message' => "Identifiant de véhicule invalide."
                    ]);
                    return;
                }

                $vehiculeId = (int) $vehiculeId;
                $vehiculeRepo = new VehiculeRepository();
                $vehicule = $vehiculeRepo->findById($vehiculeId);


                if (!$vehicule || $vehicule->getIdUtilisateurs() != $_SESSION['user_id']) {
                    throw new \Exception("Véhicule invalide ou non autorisé.");
                }

                // Conversion date + heure départ
                $dateHeureDepart = \DateTime::createFromFormat('Y-m-d H:i', "$dateDepart $heureDepart");
                if (!$dateHeureDepart) {
                    throw new \Exception("Format date/heure départ invalide.");
                }

                // Conversion date + heure arrivée
                $dateHeureArrivee = \DateTime::createFromFormat('Y-m-d H:i', "$dateArrivee $heureArrivee");
                if (!$dateHeureArrivee) {
                    throw new \Exception("Format date/heure arrivée invalide.");
                }

                $trajet = new Trajet();
                $trajet->setDateDepart($dateHeureDepart->format('Y-m-d'))
                    ->setHeureDepart($dateHeureDepart->format('H:i:s'))
                    ->setLieuDepart($depart)
                    ->setDateArrivee($dateHeureArrivee->format('Y-m-d'))
                    ->setHeureArrivee($dateHeureArrivee->format('H:i:s'))
                    ->setLieuArrivee($destination)
                    ->setStatut('en_cours')
                    ->setNbPlace($places)
                    ->setPrixPersonne($prix)
                    ->setIdUtilisateurs($_SESSION['user_id'])
                    ->setIdVehicule($vehiculeId);

                $repository = new TrajetRepository();
                $repository->save($trajet);

                echo json_encode(['success' => true]);
                return;
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
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
}
