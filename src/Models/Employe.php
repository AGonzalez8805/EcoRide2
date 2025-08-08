<?php

namespace App\Models;

use PDO;

class Employe extends Models
{
    protected ?int $id = null;
    protected ?string $email = null;
    protected ?string $password = null;
    protected ?string $pseudo = null;
    protected bool $isSuspended = false;
    protected ?int $id_admin = null;


    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     */
    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of isSuspended
     */
    public function isIsSuspended(): bool
    {
        return $this->isSuspended;
    }

    /**
     * Set the value of isSuspended
     */
    public function setIsSuspended(bool $isSuspended): self
    {
        $this->isSuspended = $isSuspended;

        return $this;
    }

    /**
     * Get the value of id_admin
     */
    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    /**
     * Set the value of id_admin
     */
    public function setIdAdmin(?int $id_admin): self
    {
        $this->id_admin = $id_admin;

        return $this;
    }
}
