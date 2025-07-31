<?php

namespace App\Models;

use PDO;

class Trajet extends Models
{
    public function getAll()
    {
        $db = $this->getConnection();

        $stmt = $db->query("SELECT * FROM trajets");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $depart, string $arrivee, string $date): array
    {
        $pdo = $this->getConnection();

        $query = "SELECT * FROM covoiturage 
                WHERE lieuDepart LIKE :depart 
                AND lieuArrivee LIKE :arrivee 
                AND dateDepart = :date";

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'depart' => "%$depart%",
                'arrivee' => "%$arrivee%",
                'date' => $date
            ]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die('Erreur SQL : ' . $e->getMessage());
        }
    }
}
