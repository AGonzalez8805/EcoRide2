<?php

namespace App\Repository;

use App\Models\User;
use PDO;

class UserRepository extends Repository
{
    /** Récupérer tous les utilisateurs */
    public function findAll(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM utilisateurs");
        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $usersArray = [];

        foreach ($rows as $row) {
            $usersArray[] = $this->hydrate(new User(), $row);
        }

        return $usersArray;
    }

    /** Trouver un utilisateur par email */
    public function findByEmail(string $email): ?User
    {
        $query = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate(new User(), $row) : null;
    }

    /** Trouver un utilisateur par ID */
    public function findById(int $id): ?User
    {
        $query = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate(new User(), $row) : null;
    }

    /** Créer un utilisateur */
    public function create(array $data): ?int
    {
        $query = $this->pdo->prepare('
            INSERT INTO utilisateurs (name, firstName, email, password, typeUtilisateur, pseudo, photo)
            VALUES (:name, :firstName, :email, :password, :typeUtilisateur, :pseudo, :photo)
            ');

        $success = $query->execute([
            ':name' => $data['name'],
            ':firstName' => $data['firstName'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':typeUtilisateur' => $data['typeUtilisateur'],
            ':pseudo' => $data['pseudo'] ?? null,
            ':photo' => $data['photo'] ?? null,
        ]);

        return $success ? (int) $this->pdo->lastInsertId() : null;
    }

    /** Mettre à jour la photo de profil */
    public function updatePhoto(int $userId, string $photoFilename): bool
    {
        $query = $this->pdo->prepare("UPDATE utilisateurs SET photo = :photo WHERE id = :id");
        return $query->execute([
            ':photo' => $photoFilename,
            ':id' => $userId
        ]);
    }

    /** Mettre à jour le pseudo */
    public function updatePseudo(int $userId, string $newPseudo): void
    {
        $query = $this->pdo->prepare("UPDATE utilisateurs SET pseudo = :pseudo WHERE id = :id");
        $query->execute([
            ':pseudo' => $newPseudo,
            ':id' => $userId
        ]);
    }

    /** Mettre à jour l'email */
    public function updateEmail(int $userId, string $newEmail): void
    {
        $query = $this->pdo->prepare("UPDATE utilisateurs SET email = :email WHERE id = :id");
        $query->execute([
            ':email' => $newEmail,
            ':id' => $userId
        ]);
    }

    /** Activer ou désactiver la suspension d'un utilsateur par son email */
    public function toggleSuspensionByEmail(string $email): bool
    {
        $query = $this->pdo->prepare("UPDATE utilisateurs SET isSuspended = NOT isSuspended WHERE email = :email");
        return $query->execute([':email' => $email]);
    }

    /** Récupérer tous les utilisateurs sauf ceux avec typeUtilisateur 'employee' (par exemple) */
    public function getAllNonEmployeeUsers(): array
    {
        $query = $this->pdo->prepare('SELECT id, name, email, isSuspended, typeUtilisateur FROM utilisateurs WHERE typeUtilisateur != :employee');
        $query->execute([':employee' => 'employee']);

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $usersArray = [];

        foreach ($rows as $row) {
            $usersArray[] = $this->hydrate(new User(), $row);
        }

        return $usersArray;
    }
}
