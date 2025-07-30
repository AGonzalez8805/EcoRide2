<?php

namespace App\Repository;

use App\Db\Mysql;

class UserRepository
{
    public function findByEmail(string $email)
    {
        //Appel bdd
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->bindValue(':email', $email, $pdo::PARAM_STR);
        $stmt->execute();

        $utilisateurs = $stmt->fetch();

        return $utilisateurs ?: null;
    }

    public function create(array $data): bool
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('
        INSERT INTO utilisateurs (name, firstName, email, password, role)
        VALUES (:name, :firstName, :email, :password, :role)');
        return $stmt->execute([
            ':name' => $data['name'],
            ':firstName' => $data['firstName'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role']
        ]);
    }

    public function findByRole(string $role)
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE role = :role LIMIT 1");
        $stmt->execute(['role' => $role]);

        return $stmt->fetch();
    }
}
