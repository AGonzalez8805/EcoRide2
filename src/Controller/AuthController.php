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
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'registration':
                        // Affichage de la page d'inscription
                        $this->registration();
                        break;
                    case 'handleRegister':
                        // Traite la soumission du formulaire d'inscription
                        $this->handleRegister();
                        break;
                    case 'login':
                        // Affichage de la page de connexion
                        $this->login();
                        break;
                    case 'handleLogin':
                        // Traite la soumission du formulaire de connexion
                        $this->handleLogin();
                        break;
                    case 'logout':
                        // Deconnexion
                        $this->logout();
                        break;

                    default:
                        // Si l'action n'est pas reconnue, on lance une exception
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                // Si aucune action n'est passée en paramètre, on lance une exception
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            // Gestion d'erreurs spécifique pour les requêtes AJAX
            if (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                // Si c'est une requête AJAX, on retourne une réponse JSON avec l'erreur
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            } else {
                // Sinon, on affiche une vue d'erreur classique
                $this->render('errors/default', [
                    'errors' => $e->getMessage()
                ]);
            }
        }
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

        // Récupération des données JSON envoyées
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Données invalides"]);
            exit;
        }

        // Nettoyage et récupération des données
        $pseudo = trim($input['pseudo'] ?? '');
        $name = trim($input['name'] ?? '');
        $firstName = trim($input['firstName'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $validatePassword = $input['validatePassword'] ?? '';

        // Vérification des champs obligatoires
        if (!$name || !$firstName || !$email || !$password) {
            echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
            exit;
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Adresse email invalide."]);
            exit;
        }

        // Vérification correspondance des mots de passe
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

        // Validation du rôle
        $role = $input['role'] ?? '';
        $allowedTypes = ['chauffeur', 'passager', 'chauffeur-passager'];

        if (!in_array($role, $allowedTypes)) {
            echo json_encode(["success" => false, "message" => "Role invalide."]);
            exit;
        }

        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Création du nouvel utilisateur dans la base de données
        $newUserId = $userRepo->create([
            'pseudo' => $pseudo,
            'name' => $name,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);

        if (!$newUserId) {
            // En cas d'erreur lors de la création
            echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription."]);
            exit;
        }

        // Répondre avec succès si tout s'est bien passé
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
            $_SESSION['typeUtilisateur'] = $user->getTypeUtilisateur();
            $_SESSION['firstName'] = $user->getFirstName();
            $_SESSION['name'] = $user->getName();

            // Redirection selon le type d'utilisateur
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
