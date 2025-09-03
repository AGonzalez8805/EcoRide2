<?php

namespace App\Controller;

use App\Models\Vehicule;
use App\Repository\VehiculeRepository;

class VehiculeController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }

            switch ($_GET['action']) {
                case 'create':
                    $this->create();
                    break;

                case 'store':
                    $this->store();
                    break;

                default:
                    throw new \Exception("Action inconnue : " . $_GET['action']);
            }
        });
    }

    public function create(): void
    {
        $from = $_GET['from'] ?? null;
        $this->render('vehicule/create', ['from' => $from]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception("Méthode non autorisée");
        }

        $marque = $_POST['marque'] ?? '';
        $modele = $_POST['modele'] ?? '';
        $couleur = $_POST['couleur'] ?? '';
        $nbPlaces = (int) ($_POST['nbPlaces'] ?? 0);
        $immatriculation = $_POST['immatriculation'] ?? '';
        $energie = $_POST['energie'] ?? '';
        $datePremierImmatriculation = $_POST['datePremierImmatriculation'] ?? '';
        $fumeur = isset($_POST['fumeur']) ? 1 : 0;
        $animaux = isset($_POST['animaux']) ? 1 : 0;

        $errors = [];

        if (!$marque) $errors['marque'] = "La marque est obligatoire.";
        if (!$modele) $errors['modele'] = "Le modèle est obligatoire.";
        if (!$couleur) $errors['couleur'] = "La couleur est obligatoire.";
        if (!$immatriculation) $errors['immatriculation'] = "L'immatriculation est obligatoire.";
        if (!$energie) $errors['energie'] = "Veuillez entrer un type d'énergie.";
        if (!$datePremierImmatriculation) $errors['datePremierImmatriculation'] = "La date de premier immatriculation est obligatoire.";
        if ($nbPlaces < 1) $errors['nbPlaces'] = "Le nombre de places doit être au moins 1.";

        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }

        $vehicule = (new Vehicule())
            ->setMarque($marque)
            ->setModele($modele)
            ->setNbPlaces($nbPlaces)
            ->setImmatriculation($immatriculation)
            ->setCouleur($couleur)
            ->setEnergie($energie)
            ->setDatePremierImmatriculation($datePremierImmatriculation)
            ->setFumeur($fumeur)
            ->setAnimaux($animaux)
            ->setIdUtilisateurs($_SESSION['user_id']);

        $repo = new VehiculeRepository();
        $repo->save($vehicule);

        // Gestion AJAX (handleRoute du parent détecte XMLHttpRequest et retourne success=false automatiquement)
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Véhicule ajouté avec succès.',
                'redirect' => '?controller=trajet&action=create'
            ]);
            return;
        }

        header('Location: ?controller=trajet&action=create');
        exit;
    }
}
