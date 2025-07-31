<?php

namespace App\Controller;

class AvisController extends Controller
{
    public function route(): void
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'avis':
                    $this->avis();
                    break;
                default:
                    throw new \Exception("");
            }
        }
    }

    protected function avis()
    {
        $this->render('avis');
    }
}
