<?php

namespace App\Controller;

class AuthController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'login':
                        $this->login();
                        break;
                    case 'handleLogin':
                        $this->handleLogin();
                        break;
                    case 'logout':
                        $this->logout();
                        break;
                    case 'registration':
                        $this->registration();
                        break;
                    case 'handleRegister':
                        $this->handleRegister();
                        break;
                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            // Gestion d'erreurs spécifique pour les requêtes AJAX
            if (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            } else {
                // Affichage d'une vue d'erreur standard
                $this->render('errors/default', [
                    'errors' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Affiche la page de connexion
     */
    public function login()
    {
        $this->render('auth/login');
    }

    public function registration()
    {
        $this->render('auth/registration');
    }

    public function handleLogin() {}

    public function logout() {}

    public function handleRegister() {}
}
