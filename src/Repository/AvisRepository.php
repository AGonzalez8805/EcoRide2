<?php

namespace App\Repository;

use MongoDB\Database;
use MongoDb\Collection;
use MongoDB\BSON\UTCDateTime;

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
}
