<?php

namespace App\Models;

use App\Db\Mysql;
use App\Tools\StringTools;
use PDO;

class Models
{
    // Récupère la connexion PDO
    protected function getConnection(): \PDO
    {
        return Mysql::getInstance()->getPDO();
    }

    // Crée une instance et hydrate ses propriétés avec les données fournies
    public static function createAndHydrate(array $data): static
    {
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }

    // Remplit l'objet avec les données passées en tableau
    public function hydrate(array $data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $methodName = 'set' . StringTools::toPascalCase($key);

                if (method_exists($this, $methodName)) {
                    if ($key == 'created_at') {
                        $value = new \DateTime($value);
                    }
                    $this->{$methodName}($value);
                }
            }
        }
    }
}
