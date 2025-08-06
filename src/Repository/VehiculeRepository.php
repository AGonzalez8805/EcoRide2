<?php

namespace App\Repository;

use App\Models\Vehicule;
use PDO;

class VehiculeRepository extends Repository
{
    public function save(Vehicule $vehicule): bool
    {
        $sql = "INSERT INTO vehicule (
                    marque, modele, couleur, immatriculation, energie, datePremierImmatriculation, nbPlaces, preferencesSupplementaires, fumeur, animaux, id_utilisateurs
                ) VALUES (
                    :marque, :modele, :couleur, :immatriculation, :energie, :datePremierImmatriculation, :nbPlaces, :preferencesSupplementaires, :fumeur, :animaux, :id_utilisateurs
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':marque'         => $vehicule->getMarque(),
            ':modele'         => $vehicule->getModele(),
            ':couleur'        => $vehicule->getCouleur(),
            ':immatriculation' => $vehicule->getImmatriculation(),
            ':energie' => $vehicule->getEnergie(),
            ':datePremierImmatriculation' => $vehicule->getDatePremierImmatriculation(),
            ':nbPlaces' => $vehicule->getNbPlaces(),
            ':preferencesSupplementaires' => $vehicule->getPreferencesSupplementaires(),
            ':fumeur' => $vehicule->isFumeur(),
            ':animaux' => $vehicule->isAnimaux(),
            ':id_utilisateurs' => $vehicule->getIdUtilisateurs(),
        ]);
    }

    public function findById(int $id): ?Vehicule
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vehicule WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydrateVehicule($data) : null;
    }

    public function findAllByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vehicule WHERE id_utilisateurs = :id_utilisateurs");
        $stmt->execute([':id_utilisateurs' => $userId]);

        $vehicules = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehicules[] = $this->hydrateVehicule($row);
        }

        return $vehicules;
    }

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

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':marque' => $vehicule->getMarque(),
            ':modele' => $vehicule->getModele(),
            ':couleur' => $vehicule->getCouleur(),
            ':immatriculation' => $vehicule->getImmatriculation(),
            ':energie' => $vehicule->getEnergie(),
            ':datePremierImmatriculation' => $vehicule->getDatePremierImmatriculation(),
            ':nbPlaces' => $vehicule->getNbPlaces(),
            ':preferencesSupplementaires' => $vehicule->getPreferencesSupplementaires(),
            ':fumeur' => $vehicule->isFumeur(),
            ':animaux' => $vehicule->isAnimaux(),
            ':id' => $vehicule->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM vehicule WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }



    private function hydrateVehicule(array $data): Vehicule
    {
        $vehicule = new Vehicule();
        return $vehicule
            ->setId($data['id'] ?? null)
            ->setMarque($data['marque'])
            ->setModele($data['modele'])
            ->setCouleur($data['couleur'])
            ->setImmatriculation($data['immatriculation'])
            ->setEnergie($data['energie'])
            ->setDatePremierImmatriculation($data['datePremierImmatriculation'])
            ->setNbPlaces($data['nbPlaces'])
            ->setPreferencesSupplementaires($data['preferencesSupplementaires'])
            ->setFumeur((bool) $data['fumeur'])
            ->setAnimaux((bool) $data['animaux'])
            ->setIdUtilisateurs((int) $data['id_utilisateurs']);
    }
}
