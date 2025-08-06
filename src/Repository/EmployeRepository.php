<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;
use App\Models\Employe;

class EmployeRepository extends Repository
{
    public function findByEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM employe WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $employe = $stmt->fetch();
        return $employe ?: null;
    }

    public function create(array $data): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

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

        $stmt = $pdo->prepare('UPDATE employe SET isSuspended = NOT isSuspended WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function setSuspendedStatus(string $email, bool $suspend): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare('UPDATE employe SET isSuspended = :suspend WHERE email = :email');
        return $stmt->execute([
            ':suspend' => (int)$suspend,
            ':email' => $email
        ]);
    }

    public function findAll(): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->query('SELECT email, pseudo, isSuspended FROM employe');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
