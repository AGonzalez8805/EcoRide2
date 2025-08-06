<?php

namespace App\Models;

use PDO;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDateDepart(): ?string
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?string $dateDepart): self
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getHeureDepart(): ?string
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(?string $heureDepart): self
    {
        $this->heureDepart = $heureDepart;
        return $this;
    }

    public function getLieuDepart(): ?string
    {
        return $this->lieuDepart;
    }

    public function setLieuDepart(?string $lieuDepart): self
    {
        $this->lieuDepart = $lieuDepart;
        return $this;
    }

    public function getDateArrivee(): ?string
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(?string $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;
        return $this;
    }

    public function getHeureArrivee(): ?string
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(?string $heureArrivee): self
    {
        $this->heureArrivee = $heureArrivee;
        return $this;
    }

    public function getLieuArrivee(): ?string
    {
        return $this->lieuArrivee;
    }

    public function setLieuArrivee(?string $lieuArrivee): self
    {
        $this->lieuArrivee = $lieuArrivee;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(?int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;
        return $this;
    }

    public function getPrixPersonne(): ?float
    {
        return $this->prixPersonne;
    }

    public function setPrixPersonne(?float $prixPersonne): self
    {
        $this->prixPersonne = $prixPersonne;
        return $this;
    }

    public function getIdUtilisateurs(): ?int
    {
        return $this->idUtilisateurs;
    }

    public function setIdUtilisateurs(?int $idUtilisateurs): self
    {
        $this->idUtilisateurs = $idUtilisateurs;
        return $this;
    }

    public function getIdVehicule(): ?int
    {
        return $this->idVehicule;
    }

    public function setIdVehicule(?int $idVehicule): self
    {
        $this->idVehicule = $idVehicule;
        return $this;
    }
}
