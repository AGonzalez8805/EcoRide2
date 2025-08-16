<?php

namespace App\Repository;

use App\Models\Vehicule;
use PDO;

class VehiculeRepository extends Repository
{
    /** Enregistrer un nouveau véhicule */
    public function save(Vehicule $vehicule): bool
    {
        $sql = "INSERT INTO vehicule (
                    marque, modele, couleur, immatriculation, energie, datePremierImmatriculation, nbPlaces, preferencesSupplementaires, fumeur, animaux, id_utilisateurs
                ) VALUES (
                    :marque, :modele, :couleur, :immatriculation, :energie, :datePremierImmatriculation, :nbPlaces, :preferencesSupplementaires, :fumeur, :animaux, :id_utilisateurs
                )";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            ':marque'                     => $vehicule->getMarque(),
            ':modele'                     => $vehicule->getModele(),
            ':couleur'                    => $vehicule->getCouleur(),
            ':immatriculation'            => $vehicule->getImmatriculation(),
            ':energie'                    => $vehicule->getEnergie(),
            ':datePremierImmatriculation' => $vehicule->getDatePremierImmatriculation(),
            ':nbPlaces'                   => $vehicule->getNbPlaces(),
            ':preferencesSupplementaires' => $vehicule->getPreferencesSupplementaires(),
            ':fumeur'                     => (int) $vehicule->isFumeur(),
            ':animaux'                    => (int) $vehicule->isAnimaux(),
            ':id_utilisateurs'            => $vehicule->getIdUtilisateurs(),
        ]);
    }

    /** Trouver un véhicule par ID */
    public function findById(int $id): ?Vehicule
    {
        $query = $this->pdo->prepare("SELECT * FROM vehicule WHERE id = :id");
        $query->execute([':id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate(new Vehicule(), $row) : null;
    }

    /** Récupérer tous les véhicules d’un utilisateur */
    public function findAllByUser(int $userId): array
    {
        $query = $this->pdo->prepare("SELECT * FROM vehicule WHERE id_utilisateurs = :id_utilisateurs");
        $query->execute([':id_utilisateurs' => $userId]);

        $vehicules = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $vehicules[] = $this->hydrate(new Vehicule(), $row);
        }

        return $vehicules;
    }

    /** Mettre à jour un véhicule */
    public function update(Vehicule $vehicule): bool
    {
        $sql = "UPDATE vehicule SET
                marque = :marque,
                modele = :modele,
                couleur = :couleur,
                immatriculation = :immatriculation,
                energie = :energie,
                datePremierImmatriculation = :datePremierImmatriculation,
                nbPlaces = :nbPlaces,
                preferencesSupplementaires = :preferencesSupplementaires,
                fumeur = :fumeur,
                animaux = :animaux
            WHERE id = :id";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            ':marque'                     => $vehicule->getMarque(),
            ':modele'                     => $vehicule->getModele(),
            ':couleur'                    => $vehicule->getCouleur(),
            ':immatriculation'            => $vehicule->getImmatriculation(),
            ':energie'                    => $vehicule->getEnergie(),
            ':datePremierImmatriculation' => $vehicule->getDatePremierImmatriculation(),
            ':nbPlaces'                   => $vehicule->getNbPlaces(),
            ':preferencesSupplementaires' => $vehicule->getPreferencesSupplementaires(),
            ':fumeur'                     => (int) $vehicule->isFumeur(),
            ':animaux'                    => (int) $vehicule->isAnimaux(),
            ':id'                         => $vehicule->getId(),
        ]);
    }

    /** Supprimer un véhicule */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare("DELETE FROM vehicule WHERE id = :id");
        return $query->execute([':id' => $id]);
    }
}
