<?php

namespace App\Repository;

use App\Db\Mysql;
use App\Tools\StringTools;
use PDO;

class Repository
{
    // Propriété pour stocker l'objet PDO
    protected \PDO $pdo;

    // Constructeur de la classe, initialise la connexion à la base de données
    public function __construct()
    {
        // Récupère l'instance de MySQL
        $mysql = Mysql::getInstance();
        // Stocke l'objet PDO pour les futures requêtes
        $this->pdo = $mysql->getPDO();
    }

    // Hydrate un objet à partir d'un tableau associatif
    protected function hydrate(object $model, array $data): object
    {
        // Parcourt chaque clé/valeur du tableau
        foreach ($data as $key => $value) {
            // Convertit la clé "id_admin" en nom de méthode "setIdAdmin"
            $method = 'set' . StringTools::toPascalCase($key);

            // Vérifie si la méthode existe dans l'objet
            if (method_exists($model, $method)) {
                // Appelle la méthode pour définir la valeur
                $model->$method($value);
            }
        }

        // Retourne l'objet hydraté
        return $model;
    }
}
