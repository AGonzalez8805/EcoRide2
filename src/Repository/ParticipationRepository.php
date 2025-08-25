<?php

namespace App\Repository;

use App\Models\Participation;
use PDO;

class ParticipationRepository extends Repository
{
    /** Récupérer les participations d’un utilisateur pour aujourd’hui */
    public function findTodayByUser(int $userId): array
    {
        $sql = "
            SELECT p.*, c.dateDepart, c.heureDepart, c.lieuDepart, c.dateArrivee, c.heureArrivee, c.lieuArrivee, c.statut AS covoiturage_statut, c.prixPersonne
            FROM participation p
            JOIN covoiturage c ON p.id_covoiturage = c.id
            WHERE p.id_utilisateurs = :userId
            AND c.dateDepart = CURDATE()
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        $participations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participation = new Participation();
            $participation->setId($row['id']);
            $participation->setIdUtilisateurs($row['id_utilisateurs']);
            $participation->setIdCovoiturage($row['id_covoiturage']);
            $participation->setDateInscription($row['dateInscription']);
            $participation->setStatut($row['statut']);
            $participations[] = $participation;
        }

        return $participations;
    }
}
