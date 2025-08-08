<?php

namespace App\Models;

use App\Db\Mysql;

use App\Tools\StringTools;

class Models
{

    protected function getConnection(): \PDO
    {
        return Mysql::getInstance()->getPDO();
    }
    public static function createAndHydrate(array $data): static
    {
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }
    public function hydrate(array $data)
    {
        if (!empty($data) > 0) {
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
