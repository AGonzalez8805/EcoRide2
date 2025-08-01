<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;

class EmployeRepository
{
    public function create(array $data): bool
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('
            INSERT INTO employe (email, pseudo, password, id_admin)
            VALUES (:email, :pseudo, :password, :id_admin)
        ');

        return $stmt->execute([
            ':email' => $data['email'],
            ':pseudo' => $data['pseudo'],
            ':password' => $data['password'],
            ':id_admin' => $data['id_admin']
        ]);
    }

    public function toggleSuspensionByEmail(string $email): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare("UPDATE utilisateurs SET isSuspended = NOT isSuspended WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function findByEmail(string $email)
    {
        $pdo = Mysql::getInstance()->getPDO();
        $stmt = $pdo->prepare('SELECT * FROM employe WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
