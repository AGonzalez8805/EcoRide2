<?php

namespace App\Repository;

use App\Models\Trajet;
use App\Models\Vehicule;
use PDO;

class TrajetRepository extends Repository
{
    /** Enregistrer un nouveau trajet */
    public function save(Trajet $trajet): bool
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

        $query = $this->pdo->prepare($sql);
        return $query->execute([
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

    /** Récupérer les trajets d'un chauffeur */
    public function findByChauffeur(int $chauffeurId): array
    {
        $sql = "SELECT * FROM covoiturage 
            WHERE id_utilisateurs = :chauffeurId 
            ORDER BY dateDepart ASC, heureDepart ASC";

        $query = $this->pdo->prepare($sql);
        $query->execute([':chauffeurId' => $chauffeurId]);

        $trajets = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $trajets[] = $this->hydrate(new Trajet(), $row);
        }
        return $trajets;
    }

    /** Récupérer les trajets du jour d'un chauffeur */
    public function findTodayByChauffeur(int $chauffeurId): array
    {
        $sql = "SELECT * FROM covoiturage 
            WHERE id_utilisateurs = :chauffeurId 
            AND dateDepart = CURDATE()
            ORDER BY heureDepart ASC";

        $query = $this->pdo->prepare($sql);
        $query->execute([':chauffeurId' => $chauffeurId]);

        $trajets = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $trajets[] = $this->hydrate(new Trajet(), $row);
        }
        return $trajets;
    }

    /** Rechercher des trajets par lieu de départ, arrivée et date */
    public function search(?string $lieuDepart = null, ?string $lieuArrivee = null, ?string $date = null): array
    {
        $sql = "SELECT * FROM covoiturage WHERE 1=1";
        $params = [];

        if ($lieuDepart) {
            $sql .= " AND lieuDepart LIKE :depart";
            $params[':depart'] = "%$lieuDepart%";
        }

        if ($lieuArrivee) {
            $sql .= " AND lieuArrivee LIKE :arrivee";
            $params[':arrivee'] = "%$lieuArrivee%";
        }

        if ($date) {
            $sql .= " AND dateDepart = :date";
            $params[':date'] = $date;
        }

        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        $trajets = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $trajets[] = $this->hydrate(new Trajet(), $row);
        }

        return $trajets;
    }

    /** Récupérer tous les trajets avec infos véhicules et chauffeur */
    public function findAllWithDetails(): array
    {
        $sql = "
        SELECT t.*, v.marque, v.modele, v.fumeur,
        u.name AS chauffeur_nom, u.firstName AS chauffeur_prenom
        FROM covoiturage t
        JOIN vehicule v ON t.id_vehicule = v.id
        JOIN utilisateurs u ON t.id_utilisateurs = u.id";

        $query = $this->pdo->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Récupère un trajet avec son véhicule par l'id du trajet */
    public function findByIdWithVehicule(int $id): ?Trajet
    {
        $sql = "SELECT t.*, v.*
                FROM covoiturage t
                JOIN vehicule v ON t.id_vehicule = v.id
                WHERE t.id = :id";

        $query = $this->pdo->prepare($sql);
        $query->execute(['id' => $id]);

        $row = $query->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null; // Trajet non trouvé
        }

        // Création de l'objet Vehicule
        $vehicule = new Vehicule();
        $vehicule->setId((int)$row['vehicule_id'])
            ->setMarque($row['marque'])
            ->setModele($row['modele'])
            ->setImmatriculation($row['immatriculation'])
            ->setEnergie($row['energie'])
            ->setCouleur($row['couleur'])
            ->setDatePremierImmatriculation($row['datePremierImmatriculation'])
            ->setNbPlaces((int)$row['nbPlaces'])
            ->setPreferencesSupplementaires($row['preferencesSupplementaires'])
            ->setFumeur((bool)$row['fumeur'])
            ->setAnimaux((bool)$row['animaux'])
            ->setIdUtilisateurs((int)$row['vehicule_proprietaire_id']);

        // Création de l'objet Trajet
        $trajet = new Trajet();
        $trajet->setId((int)$row['trajet_id'])
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
            ->setIdVehicule((int)$row['vehicule_id']);

        return $trajet;
    }
}
