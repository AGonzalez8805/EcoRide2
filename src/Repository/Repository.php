<?php

namespace App\Repository;

use App\Db\Mysql;
use App\Tools\StringTools;

class Repository
{
    protected \PDO $pdo;

    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $this->pdo = $mysql->getPDO();
    }

    /** Hydrate un objet à partir d'un tableau associatif */
    protected function hydrate(object $model, array $data): object
    {
        foreach ($data as $key => $value) {
            // Convertit "id_admin" → "IdAdmin"
            $method = 'set' . StringTools::toPascalCase($key);

            if (method_exists($model, $method)) {
                $model->$method($value);
            }
        }
        return $model;
    }
}
