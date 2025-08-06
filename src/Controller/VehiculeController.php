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
            try {
                $marque = $_POST['marque'] ?? '';
                $modele = $_POST['modele'] ?? '';
                $couleur = $_POST['couleur'] ?? '';
                $nbPlaces = (int) ($_POST['places'] ?? 1);
                $immatriculation = $_POST['immatriculation'] ?? '';
                $energie = $_POST['energie'] ?? '';
                $datePremierImmatriculation = $_POST['datePremierImmatriculation'] ?? '';
                $preferencesSupplementaires = $_POST['preferencesSupplementaires'] ?? '';
                $fumeur = $_POST['fumeur'] ?? '';
                $animaux = $_POST['animaux'] ?? '';

                $from = $_POST['from'] ?? null;

                if (
                    empty($marque) ||
                    empty($modele) ||
                    empty($couleur) ||
                    empty($immatriculation) ||
                    empty($energie) ||
                    empty($datePremierImmatriculation) ||
                    empty($preferencesSupplementaires) ||
                    empty($fumeur) ||
                    empty($animaux) ||
                    $nbPlaces < 1
                ) {
                    throw new \Exception("Tous les champs sont requis.");
                }

                $vehicule = new Vehicule();
                $vehicule->setMarque($marque)
                    ->setModele($modele)
                    ->setNbPlaces($nbPlaces)
                    ->setImmatriculation($immatriculation)
                    ->setCouleur($couleur)
                    ->setEnergie($energie)
                    ->setDatePremierImmatriculation($datePremierImmatriculation)
                    ->setPreferencesSupplementaires($preferencesSupplementaires)
                    ->setFumeur($fumeur)
                    ->setAnimaux($animaux)
                    ->setIdUtilisateurs($_SESSION['user_id']);

                $repo = new VehiculeRepository();
                $repo->save($vehicule);

                // 🔁 Redirection intelligente
                if ($from === 'trajet') {
                    header('Location: ?controller=chauffeur&action=create');
                } else {
                    header('Location: ?controller=vehicule&action=index'); // ou dashboard chauffeur
                }
                exit;
            } catch (\Exception $e) {
                $this->render('Vehicule/create', ['error' => $e->getMessage()]);
            }
        }
    }
}
