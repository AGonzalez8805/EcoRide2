<?php

namespace App\Controller;

use App\Models\Vehicule;
use App\Repository\VehiculeRepository;

class VehiculeController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
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
            }
        } catch (\Exception $e) {
            $this->render('errors/default', ['errors' => $e->getMessage()]);
        }
    }

    public function create(): void
    {
        $from = $_GET['from'] ?? null;
        $this->render('Vehicule/create', ['from' => $from]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

            try {
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

                if (empty($marque)) {
                    $errors['marque'] = "La marque est obligatoire.";
                }
                if (empty($modele)) {
                    $errors['modele'] = "Le modèle est obligatoire.";
                }
                if (empty($couleur)) {
                    $errors['couleur'] = "La couleur est obligatoire.";
                }
                if (empty($immatriculation)) {
                    $errors['immatriculation'] = "L'immatriculation est obligatoire.";
                }
                if (empty($energie)) {
                    $errors['energie'] = "Veuillez entrer un type d'énergie.";
                }
                if (empty($datePremierImmatriculation)) {
                    $errors['datePremierImmatriculation'] = "la date de premier immatriculation est obligatoire.";
                }
                if ($nbPlaces < 1) {
                    $errors['nbPlaces'] = "Le nombre de places doit être au moins 1.";
                }
                if (!empty($errors)) {
                    throw new \Exception(json_encode($errors));
                }

                $vehicule = new Vehicule();
                $vehicule->setMarque($marque)
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

                $repo = new VehiculeRepository();
                $repo->save($vehicule);

                // Debug for AJAX only
                header('Content-Type: application/json');
                die(json_encode(['success' => true, 'redirect' => '/?controller=user&action=dashboardChauffeur']));


                // Redirection normale sinon :
                header('Location: ?controller=vehicule&action=index');
                exit;
            } catch (\Exception $e) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Véhicule ajouté avec succès.']);
                    exit;

                    $msg = $e->getMessage();

                    // Ajoute un log temporaire pour t'aider à debugger
                    error_log("Erreur VehiculeController: " . $msg);

                    $decoded = json_decode($msg, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        echo json_encode([
                            'success' => false,
                            'errors' => $decoded
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => "Erreur serveur : " . $msg
                        ]);
                    }

                    http_response_code(400);
                    exit;
                }

                // Affichage classique si non-ajax
                $this->render('Vehicule/create', ['error' => $e->getMessage()]);
            }
        }
    }
}
