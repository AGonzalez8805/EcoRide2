<?php
require_once __DIR__ . '/../config/mongodb.php';

class Avis
{
    public static function getAll()
    {
        $collection = getMongoDBCollection();
        return $collection->find()->toArray();
    }

    public static function create($data)
    {
        $collection = getMongoDBCollection();
        $insertResult = $collection->insertOne([
            'nom' => $data['nom'],
            'commentaire' => $data['commentaire'],
            'note' => intval($data['note']),
            'date' => new MongoDB\BSON\UTCDateTime()
        ]);
        return $insertResult->getInsertedId();
    }
}
