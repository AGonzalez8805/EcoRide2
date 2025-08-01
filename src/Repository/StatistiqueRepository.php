<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;

class StatistiqueRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Mysql::getInstance()->getPDO();
    }

    public function getAllStats(): array
    {
        // 1. Nombre de covoiturages par jour
        $stmt1 = $this->pdo->query("
            SELECT DATE(dateDepart) AS jour, COUNT(*) AS nb 
            FROM covoiturage 
            GROUP BY jour
        ");
        $trajetsParJour = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        // 2. Crédits gagnés par jour
        $stmt2 = $this->pdo->query("
            SELECT DATE(dateOperation) AS jour, SUM(montant) AS total 
            FROM transaction 
            GROUP BY jour
        ");
        $creditsParJour = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // 3. Total des crédits
        $stmt3 = $this->pdo->query("SELECT SUM(montant) AS total FROM transaction");
        $totalCredits = $stmt3->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'trajetsParJour' => $trajetsParJour,
            'creditsParJour' => $creditsParJour,
            'totalCredits' => $totalCredits
        ];
    }
}
