<?php

namespace App\Repository;

use MongoDB\Database;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class AvisRepository
{
    private Collection $collection;

    public function __construct(Database $db)
    {
        $this->collection = $db->selectCollection('avis');
    }

    /** Ajouter un nouvel avis */
    public function ajouter(array $data): bool
    {
        try {
            $result = $this->collection->insertOne([
                'pseudo'        => $data['pseudo'] ?? null,
                'email'         => $data['email'] ?? null,
                'commentaire'   => $data['commentaire'] ?? null,
                'note'          => $data['rating'] ?? null,
                'statut'        => 'en_attente',
                'created_at'    => new UTCDateTime(),
            ]);
            return $result->getInsertedCount() === 1;
        } catch (\Exception $e) {
            return false;
        }
    }

    /** Lister tous les avis */
    public function lister(): array
    {
        $cursor = $this->collection->find([], ['sort' => ['created_at' => -1]]);
        $avisList = [];

        foreach ($cursor as $doc) {
            $avisList[] = [
                'id'          => (string) $doc->_id,
                'pseudo'      => $doc->pseudo ?? '',
                'email'       => $doc->email ?? '',
                'commentaire' => $doc->commentaire ?? '',
                'note'        => $doc->note ?? '',
                'created_at'  => $doc->created_at->toDateTime()->format('Y-m-d H:i:s'),
            ];
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
            ['_id' => new ObjectId($id)],
            ['$set' => ['statut' => $statut]]
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
            $avisList[] = [
                'id'          => (string) $doc->_id,
                'pseudo'      => $doc->pseudo ?? '',
                'commentaire' => $doc->commentaire ?? '',
                'note'        => $doc->note ?? '',
                'created_at'  => $doc->created_at->toDateTime()->format('Y-m-d H:i:s'),
            ];
        }

        return $avisList;
    }

    public function listerEnAttente(): array
    {
        $cursor = $this->collection->find(
            ['statut' => 'en_attente'],
            ['sort' => ['created_at' => -1]]
        );

        $avisList = [];
        foreach ($cursor as $doc) {
            $avisList[] = [
                'id'          => (string) $doc->_id,
                'pseudo'      => $doc->pseudo ?? '',
                'email'       => $doc->email ?? '',
                'commentaire' => $doc->commentaire ?? '',
                'note'        => $doc->note ?? '',
                'created_at'  => $doc->created_at->toDateTime()->format('Y-m-d H:i:s'),
            ];
        }

        return $avisList;
    }

    /** Compte le nombre d’avis traités (validés ou refusés) aujourd’hui */
    public function countTraitesToday(): int
    {
        // Date du jour à minuit
        $startOfDay = new UTCDateTime(strtotime('today') * 1000);
        // Date de demain à minuit (limite exclusive)
        $endOfDay = new UTCDateTime(strtotime('tomorrow') * 1000);

        return $this->collection->countDocuments([
            'statut' => ['$in' => ['valide', 'refuse']],
            'created_at' => [
                '$gte' => $startOfDay,
                '$lt'  => $endOfDay
            ]
        ]);
    }
}
