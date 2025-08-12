<?php

namespace App\Controller;

// Importation des différents contrôleurs utilisés dans le routeur principal
use App\Controller\PageController;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\AdminController;
use App\Controller\TrajetController;
use App\Controller\EmployeController;
use App\Controller\VehiculeController;

class Controller
{
    /* Méthode principale pour router la requête entrante vers le bon contrôleur */
    public function route(): void
    {
        try {
            // Vérifie si un paramètre 'controller' est présent dans l'URL (ex: ?controller=auth)
            if (isset($_GET['controller'])) {
                // En fonction de la valeur du paramètre, on appelle le contrôleur adéquat
                switch ($_GET['controller']) {
                    case 'pages':
                        // Route vers le contrôleur de pages
                        $controller = new PageController();
                        $controller->route();
                        break;

                    case 'auth':
                        // Route vers le contrôleur d'authentification (login, logout, register)
                        $controller = new AuthController();
                        $controller->route();
                        break;

                    case 'admin':
                        // Route vers le contrôleur admin
                        $controller = new AdminController();
                        $controller->route();
                        break;

                    case 'user':
                        // Route vers le contrôleur utilisateur
                        $controller = new UserController();
                        $controller->route();
                        break;

                    case 'avis':
                        // Route vers le contrôleur des avis (non importé dans le code d'origine, attention)
                        $controller = new AvisController();
                        $controller->route();
                        break;

                    case 'employe':
                        // Route vers le contrôleur employé
                        $controller = new EmployeController();
                        $controller->route();
                        break;

                    case 'covoiturage':
                        // Route vers le contrôleur trajets/covoiturage
                        $controller = new TrajetController();
                        $controller->route();
                        break;

                    case 'vehicule':
                        // Route vers le contrôleur véhicules
                        $controller = new VehiculeController();
                        $controller->route();
                        break;

                    default:
                        // Si aucun contrôleur ne correspond à la valeur reçue, lancer une exception 404
                        throw new \Exception("Le contrôleur n'existe pas", 404);
                        break;
                }
            }
        } catch (\Exception $e) {
            // En cas d'exception, afficher une page d'erreur avec le message de l'exception
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    /* Méthode utilitaire pour afficher une vue (template) avec des paramètres passés à la vue */
    protected function render(string $path, array $params = []): void
    {
        static $isRenderingError = false; // Pour éviter la récursion infinie si erreur lors du rendu d'erreur

        $filePath = APP_ROOT . '/views/' . $path . '.php';

        try {
            // Vérifie que le fichier de la vue existe
            if (!file_exists($filePath)) {
                // Lance une exception si la vue n'existe pas
                throw new \Exception("Fichier non trouvé : " . $filePath);
            } else {
                // Extrait les variables passées sous forme de tableau pour qu'elles soient accessibles dans la vue
                extract($params);

                // Inclut le fichier de la vue pour affichage
                require_once $filePath;
            }
        } catch (\Exception $e) {
            if ($isRenderingError) {
                // Si on est déjà en train de rendre une erreur (pour éviter la récursion), afficher un message simple
                echo "<h1>Erreur critique lors du rendu de la vue</h1>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                return;
            }
            // Marque qu'on est en train de gérer une erreur pour éviter récursion infinie
            $isRenderingError = true;

            // Appelle la méthode render pour afficher une vue d'erreur avec le message
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);

            // Remet le flag à false après le rendu
            $isRenderingError = false;
        }
    }
}
