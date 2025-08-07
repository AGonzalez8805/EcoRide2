<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;
use App\Models\User;

class UserRepository extends Repository
{
    public function findByEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $utilisateurs = $stmt->fetch();
        return $utilisateurs ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function updatePhoto(int $userId, string $photoFilename): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET photo = :photo WHERE id = :id");
        return $stmt->execute([
            ':photo' => $photoFilename,
            ':id' => $userId
        ]);
    }

    public function updatePseudo(int $userId, string $newPseudo): void
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET pseudo = :pseudo WHERE id = :id");
        $stmt->execute([
            ':pseudo' => $newPseudo,
            ':id' => $userId
        ]);
    }

    public function updateEmail(int $userId, string $newEmail): void
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET email = :email WHERE id = :id");
        $stmt->execute([
            ':email' => $newEmail,
            ':id' => $userId
        ]);
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
        FROM utilisateurs');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
