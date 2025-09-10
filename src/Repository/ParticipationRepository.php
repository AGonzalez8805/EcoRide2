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

    /** Récupérer le nombre de places déjà réservées pour un trajet */
    public function countPlacesByTrajet(int $trajetId): int
    {
        $sql = "SELECT COALESCE(SUM(nbPlace), 0) AS total FROM participation WHERE id_covoiturage = :trajetId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':trajetId' => $trajetId]);
        return (int) $stmt->fetchColumn();
    }

    /** Enregistrer une participation */
    public function save(Participation $participation): bool
    {
        $sql = "INSERT INTO participation 
            (id_utilisateurs, id_covoiturage, nbPlace, statut, dateInscription)
            VALUES (:userId, :trajetId, :nbPlace, :statut, :dateInscription)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $participation->getIdUtilisateurs(),
            ':trajetId' => $participation->getIdCovoiturage(),
            ':nbPlace' => $participation->getNbPlace(),
            ':statut' => $participation->getStatut(),
            ':dateInscription' => $participation->getDateInscription()
        ]);
    }

    public function userHasParticipation(int $userId, int $trajetId): bool
    {
        $sql = "SELECT COUNT(*) FROM participation 
            WHERE id_utilisateurs = :userId AND id_covoiturage = :trajetId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':userId' => $userId,
            ':trajetId' => $trajetId
        ]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function deleteByUserAndTrajet(int $userId, int $trajetId): bool
    {
        $sql = "DELETE FROM participation 
            WHERE id_utilisateurs = :userId 
                AND id_covoiturage = :trajetId";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':trajetId' => $trajetId
        ]);
    }
}
