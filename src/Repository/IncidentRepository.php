<?php

namespace App\Repository;

use MongoDB\Database;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;

class IncidentRepository
{
    private Collection $collection;

    public function __construct(Database $db)
    {
        $this->collection = $db->selectCollection('incidents');
    }

    /** Récupère tous les incidents ouverts */
    public function findOuverts(): array
    {
        $cursor = $this->collection->find(['statut' => 'ouvert']);
        return $cursor->toArray();
    }

    /** Compte les incidents ouverts */
    public function countOuverts(): int
    {
        return $this->collection->countDocuments(['statut' => 'ouvert']);
    }

    /** Ajoute un incident */
    public function insert(array $incident): string
    {
        $result = $this->collection->insertOne($incident);
        return (string) $result->getInsertedId();
    }

    /** Change le statut d’un incident */
    public function updateStatut(string $id, string $statut): void
    {
        $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => ['statut' => $statut]]
        );
    }
}
