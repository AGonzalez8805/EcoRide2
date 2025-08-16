<?php

namespace App\Models;

class Trajet extends Models
{
    protected ?int $id = null;
    protected ?string $dateDepart = null;
    protected ?string $heureDepart = null;
    protected ?string $lieuDepart = null;
    protected ?string $dateArrivee = null;
    protected ?string $heureArrivee = null;
    protected ?string $lieuArrivee = null;
    protected ?string $statut = null;
    protected ?int $nbPlace = null;
    protected ?float $prixPersonne = null;
    protected ?int $idUtilisateurs = null;
    protected ?int $idVehicule = null;

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
     * Get the value of dateDepart
     */
    public function getDateDepart(): ?string
    {
        return $this->dateDepart;
    }

    /**
     * Set the value of dateDepart
     */
    public function setDateDepart(?string $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    /**
     * Get the value of heureDepart
     */
    public function getHeureDepart(): ?string
    {
        return $this->heureDepart;
    }

    /**
     * Set the value of heureDepart
     */
    public function setHeureDepart(?string $heureDepart): self
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }

    /**
     * Get the value of lieuDepart
     */
    public function getLieuDepart(): ?string
    {
        return $this->lieuDepart;
    }

    /**
     * Set the value of lieuDepart
     */
    public function setLieuDepart(?string $lieuDepart): self
    {
        $this->lieuDepart = $lieuDepart;

        return $this;
    }

    /**
     * Get the value of dateArrivee
     */
    public function getDateArrivee(): ?string
    {
        return $this->dateArrivee;
    }

    /**
     * Set the value of dateArrivee
     */
    public function setDateArrivee(?string $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    /**
     * Get the value of heureArrivee
     */
    public function getHeureArrivee(): ?string
    {
        return $this->heureArrivee;
    }

    /**
     * Set the value of heureArrivee
     */
    public function setHeureArrivee(?string $heureArrivee): self
    {
        $this->heureArrivee = $heureArrivee;

        return $this;
    }

    /**
     * Get the value of lieuArrivee
     */
    public function getLieuArrivee(): ?string
    {
        return $this->lieuArrivee;
    }

    /**
     * Set the value of lieuArrivee
     */
    public function setLieuArrivee(?string $lieuArrivee): self
    {
        $this->lieuArrivee = $lieuArrivee;

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

    /**
     * Get the value of nbPlace
     */
    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    /**
     * Set the value of nbPlace
     */
    public function setNbPlace(?int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    /**
     * Get the value of prixPersonne
     */
    public function getPrixPersonne(): ?float
    {
        return $this->prixPersonne;
    }

    /**
     * Set the value of prixPersonne
     */
    public function setPrixPersonne(?float $prixPersonne): self
    {
        $this->prixPersonne = $prixPersonne;

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
     * Get the value of idVehicule
     */
    public function getIdVehicule(): ?int
    {
        return $this->idVehicule;
    }

    /**
     * Set the value of idVehicule
     */
    public function setIdVehicule(?int $idVehicule): self
    {
        $this->idVehicule = $idVehicule;

        return $this;
    }
}
