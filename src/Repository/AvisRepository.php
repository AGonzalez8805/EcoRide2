<?php

namespace App\Repository;

use App\Models\Avis;
use MongoDB\Database;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

class AvisRepository
{
    private Collection $collection;

    public function __construct(Database $db)
    {
        $this->collection = $db->selectCollection('avis');
    }

    /** Ajouter un nouvel avis */
    public function ajouter(Avis $avis, int $userId): bool
    {
        try {
            $doc = $avis->toDocument();

            // IDs numériques
            $doc['user_id'] = (int)$userId;
            $doc['statut'] = 'en_attente';
            $doc['created_at'] = (new \DateTime())->format(\DateTime::ATOM);

            error_log("DOC à insérer : " . print_r($doc, true));

            $result = $this->collection->insertOne($doc);

            if ($result->getInsertedCount() === 1) {
                $avis->setId((string) $result->getInsertedId());
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log("Erreur insertion avis : " . $e->getMessage());
            return false;
        }
    }

    /** Lister tous les avis d’un utilisateur */
    public function lister(int $userId): array
    {
        $cursor = $this->collection->find(
            ['user_id' => (int)$userId],
            ['sort' => ['created_at' => -1]]
        );

        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = Avis::fromDocument($doc);
        }
        return $avisList;
    }

    /** Met à jour le statut d’un avis */
    public function changerStatut(string $id, string $statut): bool
    {
        if (!in_array($statut, ['valide', 'refuse'])) {
            throw new \InvalidArgumentException("Statut invalide");
        }

        $result = $this->collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'statut' => $statut,
                'updated_at' => new UTCDateTime()
            ]]
        );

        return $result->getModifiedCount() === 1;
    }

    /** Lister uniquement les avis validés */
    public function listerValides(): array
    {
        $cursor = $this->collection->find(
            ['statut' => 'valide'],
            ['sort' => ['created_at' => -1]]
        );

        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = Avis::fromDocument($doc);
        }

        return $avisList;
    }

    /** Lister uniquement les avis en attente */
    public function listerEnAttente(): array
    {
        $cursor = $this->collection->find(
            ['statut' => 'en_attente'],
            ['sort' => ['created_at' => -1]]
        );

        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = Avis::fromDocument($doc);
        }

        return $avisList;
    }

    /** Lister tous les avis d’un utilisateur avec leur statut */
    public function listerAvecStatut(int $userId): array
    {
        $cursor = $this->collection->find(
            ['user_id' => (int)$userId],
            ['sort' => ['created_at' => -1]]
        );

        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = Avis::fromDocument($doc);
        }

        return $avisList;
    }

    /** Compte le nombre d’avis traités aujourd’hui */
    public function countTraitesToday(): int
    {
        $start = new UTCDateTime(strtotime('today') * 1000);
        $end   = new UTCDateTime(strtotime('tomorrow') * 1000);

        return $this->collection->countDocuments([
            'statut' => ['$in' => ['valide', 'refuse']],
            'updated_at' => ['$gte' => $start, '$lt' => $end]
        ]);
    }

    /** Lister tous les avis d’un chauffeur (filtré par statut valide) */
    public function listerValidesParChauffeur(int $chauffeurId): array
    {
        $cursor = $this->collection->find(
            ['chauffeur_id' => (int)$chauffeurId, 'statut' => 'valide'],
            ['sort' => ['created_at' => -1]]
        );

        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = Avis::fromDocument($doc);
        }

        return $avisList;
    }

    /** Lister par statut (générique) */
    public function listerParStatut(string $statut): array
    {
        $cursor = $this->collection->find(['statut' => $statut], ['sort' => ['created_at' => -1]]);
        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = Avis::fromDocument($doc);
        }
        return $avisList;
    }
}
