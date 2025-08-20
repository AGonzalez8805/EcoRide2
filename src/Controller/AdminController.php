<?php

namespace App\Controller;

use App\Repository\StatistiqueRepository;
use App\Repository\EmployeRepository;
use App\Repository\UserRepository;
use App\Models\Employe;
use App\Models\User;

class AdminController extends Controller
{
    private UserRepository $userRepository;
    private EmployeRepository $employeRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->employeRepository = new EmployeRepository();
    }

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


    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Charger les stats
        $statRepo = new StatistiqueRepository();
        $stats = $statRepo->getAllStats();

        // Affiche la vue admin/dashboard.php
        $this->render('admin/dashboard', ['stats' => $stats]);
    }

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

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo $message;
                exit;
            }
        }

        $this->render('admin/createEmploye', ['message' => $message]);
    }

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

        $etatActuel = $employe->isIsSuspended();
        $nouvelEtat = !$etatActuel;


        $success = $repo->setSuspendedStatus($email, $nouvelEtat);

        echo $success ? 'OK' : 'Erreur lors de la mise à jour';
    }

    public function listEmployesJson(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès refusé']);
            exit;
        }

        $repo = new EmployeRepository();
        $employes = $repo->findAll();

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

        header('Content-Type: application/json');
        echo json_encode($usersArray);
        exit;
    }

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
