<?php

namespace App\Controller;

use App\Repository\StatistiqueRepository;
use App\Repository\EmployeRepository;
use App\Repository\UserRepository;

class AdminController extends Controller
{
    public function route(): void
    {
        if (isset($_GET['action'])) {
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

                default:
                    throw new \Exception("Action administrateur inconnue");
            }
        }
    }

    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Charger les stats
        $statRepo = new \App\Repository\StatistiqueRepository();
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

                $repo = new \App\Repository\EmployeRepository();

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $created = $repo->create([
                    'email' => $email,
                    'pseudo' => $pseudo,
                    'password' => $hashedPassword,
                    'id_admin' => $_SESSION['user_id']
                ]);

                $message = $created ? 'Employé créé avec succès.' : 'Erreur lors de la création.';
            } else {
                $message = 'Tous les champs sont requis.';
            }

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTPP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo $message;
                exit;
            }
        }

        $this->render('admin/createEmploye', ['message' => $message]);
    }

    public function toggleUserStatus(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo 'Accès refusé';
            exit;
        }

        $email = $_POST['email'] ?? null;

        if (!$email) {
            http_response_code(400);
            echo 'Email manquant';
            exit;
        }

        $repo = new \App\Repository\UserRepository();

        $result = $repo->toggleSuspensionByEmail($email);
        echo $result ? 'OK' : 'Erreur';
    }

    public function toggleEmployeStatus(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo 'Accès refusé';
            exit;
        }

        $email = $_POST['email'] ?? null;

        if (!$email) {
            http_response_code(400);
            echo 'Email manquant';
            exit;
        }


        $repo = new \App\Repository\UserRepository();

        $result = $repo->toggleSuspensionByEmail($email);
        echo $result ? 'OK' : 'Erreur';
    }
}
