<?php

namespace App\Models;

class Avis extends Models
{
    protected ?int $id = null;
    protected ?string $commentaire = null;
    protected ?int $note = null;
    protected ?string $statut = null;
    protected ?string $datePublication = null;
    protected ?int $idUtilisateurs = null;
    protected ?int $idEmploye = null;

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
     * Get the value of commentaire
     */
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    /**
     * Set the value of commentaire
     */
    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get the value of note
     */
    public function getNote(): ?int
    {
        return $this->note;
    }

    /**
     * Set the value of note
     */
    public function setNote(?int $note): self
    {
        $this->note = $note;

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
     * Get the value of datePublication
     */
    public function getDatePublication(): ?string
    {
        return $this->datePublication;
    }

    /**
     * Set the value of datePublication
     */
    public function setDatePublication(?string $datePublication): self
    {
        $this->datePublication = $datePublication;

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
     * Get the value of idEmploye
     */
    public function getIdEmploye(): ?int
    {
        return $this->idEmploye;
    }

    /**
     * Set the value of idEmploye
     */
    public function setIdEmploye(?int $idEmploye): self
    {
        $this->idEmploye = $idEmploye;

        return $this;
    }
}
