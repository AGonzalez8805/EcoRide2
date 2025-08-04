<?php

namespace App\Controller;

class UserController extends Controller
{
    /**
     * Méthode de routage principale du contrôleur utilisateur.
     * Elle redirige vers la méthode appropriée en fonction de l'action passée en GET.
     */
    public function route(): void
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'dashboardChauffeur':
                    // Affiche le tableau de bord Ch
                    $this->dashboardChauffeur();
                    break;

                case 'dashboardPassager':
                    // Affiche le tableau de bord utilisateur
                    $this->dashboardPassager();
                    break;

                case 'dashboardMixte':
                    // Affiche le tableau de bord utilisateur
                    $this->dashboardMixte();
                    break;

                default:
                    // L'action n'est pas reconnue pour ce contrôleur
                    throw new \Exception("Action utilisateur inconnue");
            }
        }
    }

    public function dashboardChauffeur(): void
    {
        $this->render('user/dashboardChauffeur');
    }
    public function dashboardPassager(): void
    {
        $this->render('user/dashboardPassager');
    }
    public function dashboardMixte(): void
    {
        $this->render('user/dashboardMixte');
    }
}
