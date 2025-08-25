<?php

namespace app\Models;

use App\Models\Models;

class Participation extends Models
{
    protected ?int $id = null;
    protected ?int $idUtilisateurs = null;
    protected ?int $idCovoiturage = null;
    protected ?string $dateInscription = null;
    protected ?string $statut = null;

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
     * Get the value of idUtilisateurs
     */
    public function getIdUtilisateurs(): ?int
    {
        return $this->idUtilisateurs;
    }

    /**
     * Set the value of idUtilisateurs
     */
    public function setIdUtilisateurs(?int $idUtilisateurs): self
    {
        $this->idUtilisateurs = $idUtilisateurs;

        return $this;
    }

    /**
     * Get the value of idCovoiturage
     */
    public function getIdCovoiturage(): ?int
    {
        return $this->idCovoiturage;
    }

    /**
     * Set the value of idCovoiturage
     */
    public function setIdCovoiturage(?int $idCovoiturage): self
    {
        $this->idCovoiturage = $idCovoiturage;

        return $this;
    }

    /**
     * Get the value of dateInscription
     */
    public function getDateInscription(): ?string
    {
        return $this->dateInscription;
    }

    /**
     * Set the value of dateInscription
     */
    public function setDateInscription(?string $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get the value of statut
     */
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    /**
     * Set the value of statut
     */
    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
