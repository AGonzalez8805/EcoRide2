<?php

namespace App\Models;

use PDO;

class Vehicule extends Models
{
    private ?int $id = null;
    private ?string $marque = null;
    private ?string $modele = null;
    private ?string $immatriculation = null;
    private ?string $energie = null;
    private ?string $couleur = null;
    private ?string $datePremierImmatriculation = null;
    private ?int $nbPlaces = null;
    private ?string $preferencesSupplementaires = null;
    private bool $fumeur = false;
    private bool $animaux = false;
    protected ?int $idUtilisateurs = null;




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
     * Get the value of marque
     */
    public function getMarque(): ?string
    {
        return $this->marque;
    }

    /**
     * Set the value of marque
     */
    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get the value of modele
     */
    public function getModele(): ?string
    {
        return $this->modele;
    }

    /**
     * Set the value of modele
     */
    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get the value of immatriculation
     */
    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    /**
     * Set the value of immatriculation
     */
    public function setImmatriculation(?string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    /**
     * Get the value of energie
     */
    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    /**
     * Set the value of energie
     */
    public function setEnergie(?string $energie): self
    {
        $this->energie = $energie;

        return $this;
    }

    /**
     * Get the value of couleur
     */
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    /**
     * Set the value of couleur
     */
    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get the value of datePremierImmatriculation
     */
    public function getDatePremierImmatriculation(): ?string
    {
        return $this->datePremierImmatriculation;
    }

    /**
     * Set the value of datePremierImmatriculation
     */
    public function setDatePremierImmatriculation(?string $datePremierImmatriculation): self
    {
        $this->datePremierImmatriculation = $datePremierImmatriculation;

        return $this;
    }

    /**
     * Get the value of nbPlaces
     */
    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    /**
     * Set the value of nbPlaces
     */
    public function setNbPlaces(?int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    /**
     * Get the value of preferencesSupplementaires
     */
    public function getPreferencesSupplementaires(): ?string
    {
        return $this->preferencesSupplementaires;
    }

    /**
     * Set the value of preferencesSupplementaires
     */
    public function setPreferencesSupplementaires(?string $preferencesSupplementaires): self
    {
        $this->preferencesSupplementaires = $preferencesSupplementaires;

        return $this;
    }

    /**
     * Get the value of fumeur
     */
    public function isFumeur(): bool
    {
        return $this->fumeur;
    }

    /**
     * Set the value of fumeur
     */
    public function setFumeur(bool $fumeur): self
    {
        $this->fumeur = $fumeur;

        return $this;
    }

    /**
     * Get the value of animaux
     */
    public function isAnimaux(): bool
    {
        return $this->animaux;
    }

    /**
     * Set the value of animaux
     */
    public function setAnimaux(bool $animaux): self
    {
        $this->animaux = $animaux;

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
}
