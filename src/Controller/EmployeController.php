<?php

namespace App\Controller;

class EmployeController extends Controller
{
    public function route(): void
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'dashboard':
                    $this->dashboard();
                    break;
                default:
                    throw new \Exception("Action employé inconnue");
            }
        }
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
