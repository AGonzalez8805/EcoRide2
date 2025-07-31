<?php

namespace App\Controller;

use App\Models\Trajet;

class SearchController extends Controller
{
    public function results()
    {
        $depart = $_GET['depart'] ?? '';
        $arrivee = $_GET['arrivee'] ?? '';
        $date = $_GET['date'] ?? '';

        $trajetModel = new Trajet();
        $results = $trajetModel->search($depart, $arrivee, $date);

        var_dump($_GET);
        var_dump($results);
        exit;

        $this->render('search/results', [
            'results' => $results
        ]);
    }
}
