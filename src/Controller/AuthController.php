<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\EmployeRepository;

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

    public function handleLogin()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
            return;
        }

        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        // Vérification que les champs obligatoires sont remplis
        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "L'email et le mot de passe sont requis."]);
            return;
        }

        $adminRepo = new AdminRepository();
        $admin = $adminRepo->findByEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['pseudo'] = $admin['pseudo'];
            $_SESSION['role'] = 'admin';
            if (ob_get_length()) {
                ob_end_clean();
            }
            echo json_encode(["success" => true, "redirect" => "/?controller=admin&action=dashboard"]);
            return;
        }

        $employeRepo = new EmployeRepository();
        $employe = $employeRepo->findByEmail($email);

        if ($employe && password_verify($password, $employe['password'])) {
            $_SESSION['user_id'] = $employe['id'];
            $_SESSION['email'] = $employe['email'];
            $_SESSION['role'] = 'employe';

            if (ob_get_length()) {
                ob_end_clean();
            }
            echo json_encode(["success" => true, "redirect" => "/?controller=employe&action=dashboard"]);
            return;
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        // Vérification des identifiants
        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie, on stocke les infos en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['typeUtilisateur'] = $user['typeUtilisateur'];

            if (ob_get_length()) {
                ob_end_clean();
            }

            // Redirection conditionnelle selon letype d'utilisateur
            $typeUtilisateur = $_SESSION['typeUtilisateur'] ?? 'default';
            $url = match ($typeUtilisateur) {
                'chauffeur' => '/?controller=user&action=dashboardChauffeur',
                'passager' => '/?controller=user&action=dashboardPassager',
                'chauffeur-passager' => '/?controller=user&action=dashboardMixte',
                default => '/?controller=user&action=dashboard',
            };
            echo json_encode(["success" => true, "redirect" => $url]);
            return;
        } else {
            // Identifiants incorrects
            error_log('Session role: ' . ($_SESSION['role'] ?? 'null'));
            error_log('Session typeUtilisateur: ' . ($_SESSION['typeUtilisateur'] ?? 'null'));

            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
            return;
        }
    }

    public function logout()
    {
        session_unset();    // Supprime toutes les variables de session
        session_destroy();  // Détruit la session
        header('Location: t/?controller=auth&action=login');
        exit;
    }

    public function handleRegister()
    {
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides"]);
            exit;
        }

        // Nettoyage et validation des champs
        $pseudo = trim($input['pseudo'] ?? '');
        $name = trim($input['name'] ?? '');
        $firstName = trim($input['firstName'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $validatePassword = $input['validatePassword'] ?? '';

        if (!$name || !$firstName || !$email || !$password) {
            echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Adresse email invalide."]);
            exit;
        }

        if ($password !== $validatePassword) {
            echo json_encode(["success" => false, "message" => "Le mot de passe ne correspond pas."]);
            exit;
        }

        $userRepo = new UserRepository();

        // Vérifier que l’email n’est pas déjà utilisé
        if ($userRepo->findByEmail($email)) {
            echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé."]);
            exit;
        }

        $typeUtilisateur = $input['typeUtilisateur'] ?? '';
        $allowedTypes = ['chauffeur', 'passager', 'chauffeur-passager'];

        if (!in_array($typeUtilisateur, $allowedTypes)) {
            echo json_encode(["success" => false, "message" => "Type d'utilisateur invalide."]);
            exit;
        }


        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Créer le nouvel utilisateur
        $newUserId = $userRepo->create([
            'pseudo' => $pseudo,
            'name' => $name,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $hashedPassword,
            'typeUtilisateur' => $typeUtilisateur
        ]);

        if (!$newUserId) {
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
            exit;
        }

        // Répondre avec succès
        echo json_encode(["success" => true]);
        exit;
    }
}
