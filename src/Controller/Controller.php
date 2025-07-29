<?php

namespace App\Controller;

use App\Controller\PageController;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\AdminController;

class Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['controller'])) {
                switch ($_GET['controller']) {
                    case 'pages':
                        // Charger le contrôleur de la page
                        $pageController = new PageController();
                        $pageController->route();
                        break;

                    case 'auth':
                        // Charger le contrôleur d'authentification (login, logout, register)
                        $controller = new AuthController();
                        $controller->route();
                        break;

                    case 'admin':
                        $controller = new AdminController();
                        $controller->route();
                        break;

                    case 'user':
                        $controller = new UserController();
                        $controller->route();
                        break;


                    default:
                        throw new \Exception("Le contrôleur n'existe pas", 404);
                }
            } else {
                $pageController = new PageController();
                $pageController->home();
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
        }
    }

    protected function render(string $path, array $params = []): void
    {
        static $isRenderingError = false;
        $filePath = APP_ROOT . '/views/' . $path . '.php';

        try {
            if (!file_exists($filePath)) {
                throw new \Exception("Fichier non trouvé : " . $filePath);
            } else {
                extract($params);
                require_once $filePath;
            }
        } catch (\Exception $e) {
            if ($isRenderingError) {
                // Si on est déjà en train de rendre une erreur, afficher un message brut pour éviter la récursion
                echo "<h1>Erreur critique lors du rendu de la vue</h1>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                return;
            }
            $isRenderingError = true;
            $this->render('errors/default', [
                'errors' => $e->getMessage()
            ]);
            $isRenderingError = false;
        }
    }
}
