<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;

class UserRepository
{
    public function findByEmail(string $email)
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $utilisateurs = $stmt->fetch();
        return $utilisateurs ?: null;
    }

    public function create(array $data): ?int
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('
            INSERT INTO utilisateurs (name, firstName, email, password, typeUtilisateur)
            VALUES (:name, :firstName, :email, :password, :typeUtilisateur)');

        $success = $stmt->execute([
            ':name' => $data['name'],
            ':firstName' => $data['firstName'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':typeUtilisateur' => $data['typeUtilisateur'],
        ]);

        if ($success) {
            return (int) $pdo->lastInsertId();
        } else {
            return null;
        }
    }

    public function toggleSuspensionByEmail(string $email): bool
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare("UPDATE utilisateurs SET isSuspended = NOT isSuspended WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getAllNonEmployeeUsers(): array
    {
        $pdo = Mysql::getInstance()->getPDO();

        $stmt = $pdo->prepare('
        SELECT email, name, isSuspended, type_utilisateur
        FROM utilisateurs
    ');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
