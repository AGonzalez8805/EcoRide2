<?php

namespace App\Controller;

class EmployeController extends Controller
{
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['action'])) {
                throw new \Exception("Aucune action détectée");
            }
            switch ($_GET['action']) {
                case 'dashboard':
                    $this->dashboard();
                    break;
                default:
                    throw new \Exception("Action employé inconnue");
            }
        });
    }

    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        $this->render('employe/dashboard');
    }
}
