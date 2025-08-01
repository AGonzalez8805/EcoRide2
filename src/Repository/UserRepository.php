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

        $stmt = $pdo->prepare('
            SELECT u.*, r.name as role
            FROM utilisateurs u
            LEFT JOIN possede p ON u.id = p.id_utilisateurs
            LEFT JOIN role r ON p.id = r.id
            WHERE u.email = :email
        ');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR); // Utilisez PDO::PARAM_STR
        $stmt->execute();

        $utilisateurs = $stmt->fetch();
        return $utilisateurs ?: null;
    }

    public function create(array $data): ?int
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare('
            INSERT INTO utilisateurs (name, firstName, email, password)
            VALUES (:name, :firstName, :email, :password)');

        $success = $stmt->execute([
            ':name' => $data['name'],
            ':firstName' => $data['firstName'],
            ':email' => $data['email'],
            ':password' => $data['password'],
        ]);

        if ($success) {
            return (int) $pdo->lastInsertId();
        } else {
            return null;
        }
    }

    public function existsUserWithRole(string $roleName): bool
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $sql = "SELECT COUNT(*) FROM possede p
                JOIN role r ON p.id = r.id
                WHERE r.name = :roleName";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['roleName' => $roleName]);

        return $stmt->fetchColumn() > 0;
    }

    public function assignRoleToUser(int $userId, string $roleName): bool
    {
        $mysql = Mysql::getInstance();
        $pdo = $mysql->getPDO();

        $stmt = $pdo->prepare("SELECT id FROM role WHERE name = :roleName");
        $stmt->execute(['roleName' => $roleName]);
        $roleId = $stmt->fetchColumn();

        if (!$roleId) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO possede (id, id_utilisateurs) VALUES (:id, :userId)");
        return $stmt->execute([':id' => $roleId, 'userId' => $userId]);
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

        $sql = "
        SELECT u.email, u.name, u.isSuspended
        FROM utilisateurs u
        LEFT JOIN possede p ON u.id = p.id_utilisateurs
        LEFT JOIN role r ON p.id = r.id
        WHERE r.name != 'employe' OR r.name IS NULL
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
