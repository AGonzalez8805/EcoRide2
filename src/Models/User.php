<?php

namespace App\Models;

class User extends Models
{
    protected ?int $id = null;
    protected ?string $email = null;
    protected ?string $password = null;
    protected ?string $name = null;
    protected ?string $firstName = null;
    protected ?string $role = null;
    protected ?string $typeUtilisateur = null;
    protected ?string $pseudo = null;
    protected ?string $photo = null;
    protected ?bool $isSuspended = false;
    protected ?int $credit = 20;


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
     * Get the value of name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of role
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Set the value of role
     */
    public function setRole(?string $role): self
    {
        $this->role = $role;

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
     * Get the value of photo
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     */
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of isSuspended
     */
    public function isIsSuspended(): ?bool
    {
        return $this->isSuspended;
    }

    /**
     * Set the value of isSuspended
     */
    public function setIsSuspended(?bool $isSuspended): self
    {
        $this->isSuspended = $isSuspended;

        return $this;
    }

    /**
     * Get the value of credit
     */
    public function getCredit(): ?int
    {
        return $this->credit;
    }

    /**
     * Set the value of credit
     */
    public function setCredit(?int $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get the value of typeUtilisateur
     */
    public function getTypeUtilisateur(): ?string
    {
        return $this->typeUtilisateur;
    }

    /**
     * Set the value of typeUtilisateur
     */
    public function setTypeUtilisateur(?string $typeUtilisateur): self
    {
        $this->typeUtilisateur = $typeUtilisateur;

        return $this;
    }
}
