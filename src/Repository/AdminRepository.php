<?php

namespace App\Repository;

use App\Models\Admin;
use PDO;

class AdminRepository extends Repository
{
    /** Récupérer tous les administrateurs */
    public function findAll(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM admin");
        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $admins = [];

        foreach ($rows as $row) {
            $admins[] = $this->hydrate(new Admin(), $row);
        }

        return $admins;
    }

    /** Trouver un admin par son email */
    public function findByEmail(string $email): ?Admin
    {
        $query = $this->pdo->prepare('SELECT * FROM admin WHERE email = :email');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate(new Admin(), $row) : null;
    }

    /** Trouver un admin par ID */
    public function findById(int $id): ?Admin
    {
        $query = $this->pdo->prepare("SELECT * FROM admin WHERE id = :id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate(new Admin(), $row) : null;
    }

    /**
     * Créer un admin
     */
    protected function create(array $data): bool
    {
        $query = $this->pdo->prepare('
            INSERT INTO admin (email, pseudo, password)
            VALUES (:email, :pseudo, :password)
        ');

        return $query->execute([
            ':email'    => $data['email'],
            ':pseudo'   => $data['pseudo'],
            ':password' => $data['password'],
        ]);
    }

    /** Mettre à jour le mot de passe */
    public function updatePassword(string $email, string $hashedPassword): bool
    {
        $query = $this->pdo->prepare('
            UPDATE admin SET password = :password
            WHERE email = :email');

        return $query->execute([
            ':password' => $hashedPassword,
            ':email'    => $email,
        ]);
    }
}
