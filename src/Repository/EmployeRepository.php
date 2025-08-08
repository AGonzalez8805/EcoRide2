<?php

namespace App\Repository;

use PDO;
use App\Models\Employe;

class EmployeRepository extends Repository
{
    /** Trouver un employé par son email */
    public function findByEmail(string $email): ?Employe
    {
        $query = $this->pdo->prepare('SELECT * FROM employe WHERE email = :email');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate(new Employe(), $row) : null;
    }

    /** Créer un employé (uniquement via admin) */
    public function create(array $data): bool
    {
        $query = $this->pdo->prepare('
            INSERT INTO employe (email, pseudo, password, id_admin)
            VALUES (:email, :pseudo, :password, :id_admin)
        ');

        return $query->execute([
            ':email'    => $data['email'],
            ':pseudo'   => $data['pseudo'],
            ':password' => $data['password'],
            ':id_admin' => $data['id_admin']
        ]);
    }

    /** Activer/désactiver suspension par email */
    public function toggleSuspensionByEmail(string $email): bool
    {
        $query = $this->pdo->prepare('
            UPDATE employe SET isSuspended = NOT isSuspended 
            WHERE email = :email');

        return $query->execute([':email' => $email]);
    }

    /** Mettre un état de suspension spécifique */
    public function setSuspendedStatus(string $email, bool $suspend): bool
    {
        $query = $this->pdo->prepare('
            UPDATE employe SET isSuspended = :suspend 
            WHERE email = :email');

        return $query->execute([
            ':suspend' => (int)$suspend,
            ':email'   => $email
        ]);
    }

    /** Lister tous les employés */
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM employe');
        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $employes = [];

        foreach ($rows as $row) {
            $employes[] = $this->hydrate(new Employe(), $row);
        }

        return $employes;
    }
}
