<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\EmployeRepository;

class AuthController extends Controller
{
    // Méthode principale pour router les actions en fonction du paramètre GET 'action'
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }

            switch ($_GET['action']) {
                case 'registration':
                    $this->registration();
                    break;
                case 'handleRegister':
                    $this->handleRegister();
                    break;
                case 'login':
                    $this->login();
                    break;
                case 'handleLogin':
                    $this->handleLogin();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                default:
                    throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
            }
        });
    }

    /* Affiche la page d'inscription */
    public function registration()
    {
        $this->render('auth/registration');
    }

    /* Affiche la page de connexion */
    public function login()
    {
        $this->render('auth/login');
    }

    /* Traite l'inscription d'un nouvel utilisateur */
    public function handleRegister()
    {
        header('Content-Type: application/json');
        http_response_code(200);

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides"]);
            exit;
        }

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

        if ($userRepo->findByEmail($email)) {
            echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé."]);
            exit;
        }

        // Générer un pseudo unique
        $pseudo = trim($input['pseudo'] ?? '');

        if (empty($pseudo)) {
            $pseudo = 'user_' . uniqid();
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // typeUtilisateur non défini
        $typeUtilisateur = 'non-defini';

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

        echo json_encode(["success" => true]);
        exit;
    }

    /* Traite la connexion de l'utilisateur */
    public function handleLogin()
    {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nettoyer tous les buffers de sortie pour éviter les sorties HTML parasites
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/json'); // On prépare la réponse JSON
        http_response_code(200);

        // Récupérer les données JSON envoyées en POST
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
            return;
        }

        // Récupérer et nettoyer les valeurs
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        // Vérifier que l'email et le mot de passe sont renseignés
        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "L'email et le mot de passe sont requis."]);
            exit;
        }

        // Vérification dans la table admin
        $adminRepo = new AdminRepository();
        $admin = $adminRepo->findByEmail($email);

        if ($admin && password_verify($password, $admin->getPassword())) {
            // Authentification réussie pour un admin
            $_SESSION['user_id'] = $admin->getId();
            $_SESSION['pseudo'] = $admin->getPseudo();
            $_SESSION['role'] = 'admin';

            echo json_encode(["success" => true, "redirect" => "/?controller=admin&action=dashboard"]);
            return;
        }

        // Vérification dans la table employe
        $employeRepo = new EmployeRepository();
        $employe = $employeRepo->findByEmail($email);

        if ($employe && password_verify($password, $employe->getPassword())) {
            // Authentification réussie pour un employé
            $_SESSION['user_id'] = $employe->getId();
            $_SESSION['email'] = $employe->getEmail();
            $_SESSION['pseudo'] = $employe->getPseudo();
            $_SESSION['firstName'] = $employe->getFirstName();
            $_SESSION['name'] = $employe->getName();
            $_SESSION['role'] = 'employe';

            echo json_encode(["success" => true, "redirect" => "/?controller=employe&action=dashboard"]);
            return;
        }

        // Vérification dans la table utilisateur
        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Authentification réussie pour un utilisateur classique
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['typeUtilisateur'] = $user->getRole();
            $_SESSION['firstName'] = $user->getFirstName();
            $_SESSION['name'] = $user->getName();

            // Redirection selon le type d'utilisateur
            $typeUtilisateur = $_SESSION['typeUtilisateur'] ?? 'default';
            $url = match ($typeUtilisateur) {
                'chauffeur' => '/?controller=user&action=dashboardChauffeur',
                'passager' => '/?controller=user&action=dashboardPassager',
                'chauffeur-passager' => '/?controller=user&action=dashboardMixte',
                default => '/?controller=user&action=profil',
            };
            echo json_encode(["success" => true, "redirect" => $url]);
            return;
        } else {
            // En cas d'échec d'authentification
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
            return;
        }
    }

    /* Déconnecte l'utilisateur */
    public function logout()
    {
        session_unset();    // Supprime toutes les variables de session
        session_destroy();  // Détruit la session
        header('Location: t/?controller=auth&action=login'); // Redirection vers la page de connexion
        exit;
    }
}
