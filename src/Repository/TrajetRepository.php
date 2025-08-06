<?php

namespace App\Repository;

use App\Models\Trajet;
use PDO;

class TrajetRepository extends Repository
{
    public function save(Trajet $trajet): void
    {
        $sql = "INSERT INTO covoiturage (
                dateDepart, heureDepart, lieuDepart,
                dateArrivee, heureArrivee, lieuArrivee,
                statut, nbPlace, prixPersonne,
                id_utilisateurs, id_vehicule
            ) VALUES (
                :dateDepart, :heureDepart, :lieuDepart,
                :dateArrivee, :heureArrivee, :lieuArrivee,
                :statut, :nbPlace, :prixPersonne,
                :id_utilisateurs, :id_vehicule
            )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':dateDepart'     => $trajet->getDateDepart(),
            ':heureDepart'    => $trajet->getHeureDepart(),
            ':lieuDepart'     => $trajet->getLieuDepart(),
            ':dateArrivee'    => $trajet->getDateArrivee(),
            ':heureArrivee'   => $trajet->getHeureArrivee(),
            ':lieuArrivee'    => $trajet->getLieuArrivee(),
            ':statut'         => $trajet->getStatut(),
            ':nbPlace'        => $trajet->getNbPlace(),
            ':prixPersonne'   => $trajet->getPrixPersonne(),
            ':id_utilisateurs' => $trajet->getIdUtilisateurs(),
            ':id_vehicule'     => $trajet->getIdVehicule(),
        ]);
    }


    // Exemple : récupérer les trajets d’un chauffeur
    public function findByChauffeur(int $chauffeurId): array
    {
        $sql = "SELECT * FROM covoiturage 
            WHERE id_utilisateurs = :chauffeurId 
            ORDER BY dateDepart ASC, heureDepart ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':chauffeurId' => $chauffeurId]);

        $trajets = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $trajet = new Trajet();
            $trajet->setId($row['id'] ?? null)
                ->setDateDepart($row['dateDepart'])
                ->setHeureDepart($row['heureDepart'])
                ->setLieuDepart($row['lieuDepart'])
                ->setDateArrivee($row['dateArrivee'])
                ->setHeureArrivee($row['heureArrivee'])
                ->setLieuArrivee($row['lieuArrivee'])
                ->setStatut($row['statut'])
                ->setNbPlace((int)$row['nbPlace'])
                ->setPrixPersonne((float)$row['prixPersonne'])
                ->setIdUtilisateurs((int)$row['id_utilisateurs'])
                ->setIdVehicule((int)$row['id_vehicule']);

            $trajets[] = $trajet;
        }
        return $trajets;
    }

    public function search(string $depart, string $arrivee, string $date): array
    {
        $sql = "SELECT * FROM covoiturage WHERE 1=1";
        $params = [];

        if (!empty($depart)) {
            $sql .= " AND lieuDepart LIKE :depart";
            $params[':depart'] = "%$depart%";
        }

        if (!empty($arrivee)) {
            $sql .= " AND lieuArrivee LIKE :arrivee";
            $params[':arrivee'] = "%$arrivee%";
        }

        if (!empty($date)) {
            $sql .= " AND dateDepart = :date";
            $params[':date'] = $date;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $trajets = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $trajets[] = $this->hydrateTrajet($row);
        }

        return $trajets;
    }

    private function hydrateTrajet(array $row): Trajet
    {
        $trajet = new Trajet();
        return $trajet
            ->setId($row['id'] ?? null)
            ->setDateDepart($row['dateDepart'])
            ->setHeureDepart($row['heureDepart'])
            ->setLieuDepart($row['lieuDepart'])
            ->setDateArrivee($row['dateArrivee'])
            ->setHeureArrivee($row['heureArrivee'])
            ->setLieuArrivee($row['lieuArrivee'])
            ->setStatut($row['statut'])
            ->setNbPlace((int)$row['nbPlace'])
            ->setPrixPersonne((float)$row['prixPersonne'])
            ->setIdUtilisateurs((int)$row['id_utilisateurs'])
            ->setIdVehicule((int)$row['id_vehicule']);
    }
}
