<?php

namespace App\Controller;

use App\Repository\StatistiqueRepository;
use App\Repository\EmployeRepository;
use App\Repository\UserRepository;
use App\Models\Employe;
use App\Models\User;

/**
 * Contrôleur dédié à la partie Administration.
 * Gère les actions liées au dashboard, à la gestion des employés et des utilisateurs.
 */
class AdminController extends Controller
{
    private UserRepository $userRepository;
    private EmployeRepository $employeRepository;

    /**
     * Constructeur : initialise les repositories nécessaires.
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->employeRepository = new EmployeRepository();
    }

    /**
     * Router interne du contrôleur Admin.
     * Permet de déclencher la bonne méthode en fonction du paramètre GET['action'].
     */
    public function route(): void
    {
        $this->handleRoute(function () {
            if (!isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }
            switch ($_GET['action']) {
                case 'dashboard':
                    $this->dashboard();
                    break;
                case 'createEmploye':
                    $this->createEmploye();
                    break;
                case 'toggleUserStatus':
                    $this->toggleUserStatus();
                    break;
                case 'toggleEmployeStatus':
                    $this->toggleEmployeStatus();
                    break;
                case 'listEmployesJson':
                    $this->listEmployesJson();
                    break;
                case 'listUsersJson':
                    $this->listUsersJson();
                    break;
                default:
                    throw new \Exception("Action administrateur inconnue");
            }
        });
    }

    /**
     * Affiche le tableau de bord Admin avec les statistiques globales.
     * Accessible uniquement si l'utilisateur est connecté en tant qu'admin.
     */
    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Charger les statistiques depuis le repository
        $statRepo = new StatistiqueRepository();
        $stats = $statRepo->getAllStats();

        // Affiche la vue correspondante
        $this->render('admin/dashboard', ['stats' => $stats]);
    }

    /**
     * Crée un nouvel employé.
     * - Vérifie que l'utilisateur est admin
     * - Récupère les champs envoyés via POST
     * - Hash le mot de passe et enregistre dans la BDD
     * - Peut répondre en AJAX ou afficher une vue classique
     */
    public function createEmploye(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?controller=auth&action=login');
            exit;
        }
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $pseudo = $_POST['pseudo'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($pseudo) && !empty($password)) {

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Création dans le repository
                $created = $this->employeRepository->create([
                    'email' => $email,
                    'pseudo' => $pseudo,
                    'password' => $hashedPassword,
                    'id_admin' => $_SESSION['user_id']
                ]);

                $message = $created ? 'Employé créé avec succès.' : 'Erreur lors de la création.';
            } else {
                $message = 'Tous les champs sont requis.';
            }

            // Réponse AJAX si nécessaire
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo $message;
                exit;
            }
        }

        // Affichage de la vue création employé
        $this->render('admin/createEmploye', ['message' => $message]);
    }

    /**
     * Active/désactive le statut d’un employé (suspendu ou non).
     * - Vérifie que l’admin est connecté
     * - Cherche l’employé par email
     * - Inverse son état (suspendu/actif)
     */
    public function toggleEmployeStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Méthode non autorisée';
            return;
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo 'Non autorisé';
            return;
        }

        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            http_response_code(400);
            echo 'Email manquant';
            return;
        }

        $repo = new EmployeRepository();
        $employe = $repo->findByEmail($email);

        if (!$employe) {
            http_response_code(404);
            echo 'Employé introuvable';
            return;
        }

        // Inverse l'état de suspension
        $etatActuel = $employe->isIsSuspended();
        $nouvelEtat = !$etatActuel;

        $success = $repo->setSuspendedStatus($email, $nouvelEtat);

        echo $success ? 'OK' : 'Erreur lors de la mise à jour';
    }

    /**
     * Retourne la liste de tous les employés au format JSON.
     * - Vérifie que l’utilisateur est admin
     * - Utilisé par des appels AJAX côté front (DataTables, Vue.js, etc.)
     */
    public function listEmployesJson(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès refusé']);
            exit;
        }

        $repo = new EmployeRepository();
        $employes = $repo->findAll();

        // Transformation en tableau simple
        $employeesArray = array_map(function (Employe $employe) {
            return [
                'id'          => $employe->getId(),
                'email'       => $employe->getEmail(),
                'pseudo'      => $employe->getPseudo(),
                'isSuspended' => $employe->isIsSuspended(),
            ];
        }, $employes);

        header('Content-Type: application/json');
        echo json_encode($employeesArray);
        exit;
    }

    /**
     * Retourne la liste de tous les utilisateurs (hors employés) au format JSON.
     */
    public function listUsersJson()
    {
        header('Content-Type: application/json');
        $users = $this->userRepository->getAllNonEmployeeUsers();

        $usersArray = array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'firstName' => $user->getFirstName(),
                'pseudo' => $user->getPseudo(),
                'isSuspended' => $user->isIsSuspended(),
            ];
        }, $users);

        echo json_encode($usersArray);
        exit;
    }

    /**
     * Active/désactive un utilisateur (non employé).
     * - Reçoit un email en POST
     * - Appelle le repository pour inverser le statut
     */
    public function toggleUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
            $email = $_POST['email'];
            $success = $this->userRepository->toggleSuspensionByEmail($email);
            echo $success ? "OK" : "Erreur lors de la modification";
        } else {
            echo "Requête invalide";
        }
    }
}
