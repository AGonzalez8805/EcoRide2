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
        SELECT t.id AS trajet_id, t.dateDepart, t.heureDepart, t.lieuDepart,
                t.dateArrivee, t.heureArrivee, t.lieuArrivee,
                t.statut, t.nbPlace, t.prixPersonne,
                v.id AS vehicule_id, v.marque, v.modele, v.fumeur,
                u.id AS chauffeur_id, u.name AS chauffeur_nom, u.firstName AS chauffeur_prenom, u.photo AS chauffeur_photo
        FROM covoiturage t
        JOIN vehicule v ON t.id_vehicule = v.id
        JOIN utilisateurs u ON t.id_utilisateurs = u.id
        WHERE t.statut = 'en_cours'
            AND (t.dateDepart > CURDATE() OR (t.dateDepart = CURDATE() AND t.heureDepart >= CURTIME()))
        ORDER BY t.dateDepart ASC, t.heureDepart ASC
            ";

        $query = $this->pdo->query($sql);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $trajets = [];
        foreach ($rows as $row) {
            $vehicule = (new Vehicule())
                ->setId((int)$row['vehicule_id'])
                ->setMarque($row['marque'])
                ->setModele($row['modele'])
                ->setFumeur((bool)$row['fumeur']);

            $trajet = (new Trajet())
                ->setId((int)$row['trajet_id'])
                ->setDateDepart($row['dateDepart'])
                ->setHeureDepart($row['heureDepart'])
                ->setLieuDepart($row['lieuDepart'])
                ->setDateArrivee($row['dateArrivee'])
                ->setHeureArrivee($row['heureArrivee'])
                ->setLieuArrivee($row['lieuArrivee'])
                ->setStatut($row['statut'])
                ->setNbPlace((int)$row['nbPlace'])
                ->setPrixPersonne((float)$row['prixPersonne'])
                ->setIdVehicule((int)$row['vehicule_id'])
                ->setIdUtilisateurs((int)$row['chauffeur_id'])
                ->setVehicule($vehicule)
                ->setChauffeurNom($row['chauffeur_nom'])
                ->setChauffeurPrenom($row['chauffeur_prenom'])
                ->setChauffeurPhoto($row['chauffeur_photo']);

            $trajets[] = $trajet;
        }

        return $trajets;
    }


    public function searchWithDetails(?string $lieuDepart = null, ?string $lieuArrivee = null, ?string $date = null): array
    {
        $sql = "
        SELECT t.id AS trajet_id, t.dateDepart, t.heureDepart, t.lieuDepart,
                t.dateArrivee, t.heureArrivee, t.lieuArrivee,
                t.statut, t.nbPlace, t.prixPersonne,
                v.id AS vehicule_id, v.marque, v.modele, v.fumeur,
                u.id AS chauffeur_id, u.name AS chauffeur_nom, u.firstName AS chauffeur_prenom, u.photo AS chauffeur_photo
        FROM covoiturage t
        JOIN vehicule v ON t.id_vehicule = v.id
        JOIN utilisateurs u ON t.id_utilisateurs = u.id
        WHERE t.statut = 'en_cours'
            AND (t.dateDepart > CURDATE() OR (t.dateDepart = CURDATE() AND t.heureDepart >= CURTIME()))
        ";

        $params = [];

        if ($lieuDepart) {
            $sql .= " AND t.lieuDepart LIKE :depart";
            $params[':depart'] = "%$lieuDepart%";
        }
        if ($lieuArrivee) {
            $sql .= " AND t.lieuArrivee LIKE :arrivee";
            $params[':arrivee'] = "%$lieuArrivee%";
        }
        if ($date) {
            $sql .= " AND t.dateDepart = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY t.dateDepart ASC, t.heureDepart ASC";

        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        $trajets = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $vehicule = (new Vehicule())
                ->setId((int)$row['vehicule_id'])
                ->setMarque($row['marque'])
                ->setModele($row['modele'])
                ->setFumeur((bool)$row['fumeur']);

            $trajet = (new Trajet())
                ->setId((int)$row['trajet_id'])
                ->setDateDepart($row['dateDepart'])
                ->setHeureDepart($row['heureDepart'])
                ->setLieuDepart($row['lieuDepart'])
                ->setDateArrivee($row['dateArrivee'])
                ->setHeureArrivee($row['heureArrivee'])
                ->setLieuArrivee($row['lieuArrivee'])
                ->setStatut($row['statut'])
                ->setNbPlace((int)$row['nbPlace'])
                ->setPrixPersonne((float)$row['prixPersonne'])
                ->setIdVehicule((int)$row['vehicule_id'])
                ->setIdUtilisateurs((int)$row['chauffeur_id'])
                ->setVehicule($vehicule)
                ->setChauffeurNom($row['chauffeur_nom'])
                ->setChauffeurPrenom($row['chauffeur_prenom'])
                ->setChauffeurPhoto($row['chauffeur_photo']);

            $trajets[] = $trajet;
        }

        return $trajets;
    }


    /** Récupère un trajet avec son véhicule par l'id du trajet */
    public function findByIdWithVehicule(int $id): ?Trajet
    {
        $sql = "SELECT 
            t.id AS trajet_id, t.dateDepart, t.heureDepart, t.lieuDepart,
            t.dateArrivee, t.heureArrivee, t.lieuArrivee,
            t.statut, t.nbPlace, t.prixPersonne, t.id_utilisateurs,
            v.id AS vehicule_id, v.marque, v.modele, v.immatriculation, 
            v.energie, v.couleur, v.datePremierImmatriculation, 
            v.nbPlaces, v.preferencesSupplementaires, v.fumeur, v.animaux, 
            v.id_utilisateurs AS vehicule_proprietaire_id
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

    /** Compter le nombre de trajets d’un chauffeur dans le mois courant */
    public function countByMonth(int $chauffeurId): int
    {
        $sql = "SELECT COUNT(*) 
            FROM covoiturage 
            WHERE id_utilisateurs = :chauffeurId
            AND MONTH(dateDepart) = MONTH(CURDATE())
            AND YEAR(dateDepart) = YEAR(CURDATE())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':chauffeurId' => $chauffeurId]);
        return (int)$stmt->fetchColumn();
    }

    /** Compter le nombre total de passagers transportés par un chauffeur */
    public function countPassagersTransportes(int $chauffeurId): int
    {
        $sql = "SELECT COALESCE(SUM(pr.nbPlace),0)
            FROM participation pr
            JOIN covoiturage t ON pr.id_covoiturage = t.id
            WHERE t.id_utilisateurs = :chauffeurId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':chauffeurId' => $chauffeurId]);
        return (int)$stmt->fetchColumn();
    }

    public function findById(int $id): ?Trajet
    {
        $sql = "SELECT c.*, u.firstName AS chauffeurPrenom, u.name AS chauffeurNom, u.photo AS chauffeurPhoto
            FROM covoiturage c
            JOIN utilisateurs u ON c.id_utilisateurs = u.id
            WHERE c.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $trajet = new Trajet();
            $trajet->setId($row['id']);
            $trajet->setLieuDepart($row['lieuDepart']);
            $trajet->setLieuArrivee($row['lieuArrivee']);
            $trajet->setDateDepart($row['dateDepart']);
            $trajet->setHeureDepart($row['heureDepart']);
            $trajet->setPrixPersonne($row['prixPersonne']);
            $trajet->setIdUtilisateurs($row['id_utilisateurs']);

            // 👇 On enrichit avec les infos chauffeur
            $trajet->setChauffeurPrenom($row['chauffeurPrenom']);
            $trajet->setChauffeurNom($row['chauffeurNom']);

            return $trajet;
        }

        return null;
    }
}
