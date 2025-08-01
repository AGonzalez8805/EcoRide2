<?php

namespace App\Controller;

class CovoiturageController extends Controller
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

            default:
                throw new \Exception('Action covoiturage inconnue : $action', 404);
        }
    }

    public function covoiturage(): void
    {
        $this->render('covoiturage/covoiturage');
    }

    public function ajouter(): void
    {
        $this->render('covoiturage/ajouter');
    }
}
