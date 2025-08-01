<?php

namespace App\Repository;

use App\Db\Mysql;

class AdminRepository
{
    public function findByEmail(string $email)
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('SELECT * FROM admin WHERE email = :email');
        $stmt->bindValue(':email', $email, $pdo::PARAM_STR);
        $stmt->execute();

        $admin = $stmt->fetch();
        return $admin ?: null;
    }
    public function create(array $data): bool
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('
            INSERT INTO admin (email, pseudo, password)
            VALUES (:email, :pseudo, :password)
        ');

        return $stmt->execute([
            ':email' => $data['email'],
            ':pseudo' => $data['pseudo'],
            ':password' => $data['password'],
        ]);
    }
}
