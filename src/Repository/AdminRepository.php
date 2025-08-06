<?php

namespace App\Repository;

use App\Db\Mysql;
use App\Models\Admin;
use PDO;

class AdminRepository extends Repository
{
    public function findByEmail(string $email): ?Admin
    {
        $stmt = $this->pdo->prepare('SELECT * FROM admin WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Admin($data) : null;
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
