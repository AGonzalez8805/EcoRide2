<?php

namespace App\Models;

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class Avis
{
    private ?string $id = null;
    private ?string $commentaire = null;
    private ?int $note = null;
    private ?string $statut = null;
    private ?\DateTime $datePublication = null;
    private ?string $idUtilisateurs = null;
    private ?string $idEmploye = null;
    private ?string $chauffeurId = null;
    private ?string $pseudo = null;

    public function __construct(array $data = [])
    {
        $this->id              = $data['id'] ?? null;
        $this->commentaire     = $data['commentaire'] ?? null;
        $this->note            = $data['note'] ?? null;
        $this->statut          = $data['statut'] ?? null;
        $this->datePublication = $data['datePublication'] ?? null;
        $this->idUtilisateurs  = $data['idUtilisateurs'] ?? null;
        $this->idEmploye       = $data['idEmploye'] ?? null;
        $this->chauffeurId     = $data['chauffeur_id'] ?? null;
        $this->pseudo          = $data['pseudo'] ?? null;
    }

    /**
     * Get the value of id
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?string $id): self
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
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set the value of datePublication
     */
    public function setDatePublication($datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get the value of idUtilisateurs
     */
    public function getIdUtilisateurs(): ?string
    {
        return $this->idUtilisateurs;
    }

    /**
     * Set the value of idUtilisateurs
     */
    public function setIdUtilisateurs(?string $idUtilisateurs): self
    {
        $this->idUtilisateurs = $idUtilisateurs;

        return $this;
    }

    /**
     * Get the value of idEmploye
     */
    public function getIdEmploye(): ?string
    {
        return $this->idEmploye;
    }

    /**
     * Set the value of idEmploye
     */
    public function setIdEmploye(?string $idEmploye): self
    {
        $this->idEmploye = $idEmploye;

        return $this;
    }


    /**
     * Get the value of chauffeurId
     */
    public function getChauffeurId(): ?string
    {
        return $this->chauffeurId;
    }

    /**
     * Set the value of chauffeurId
     */
    public function setChauffeurId(?string $chauffeurId): self
    {
        $this->chauffeurId = $chauffeurId;

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

    public static function fromDocument(object $doc): self
    {
        return new self([
            'id'              => isset($doc->_id) ? (string)$doc->_id : null,
            'commentaire'     => $doc->commentaire ?? null,
            'note'            => $doc->note ?? null,
            'statut'          => $doc->statut ?? 'en_attente',
            'datePublication' => isset($doc->created_at) && $doc->created_at instanceof UTCDateTime
                ? $doc->created_at->toDateTime()
                : null,
            'idUtilisateurs'  => isset($doc->user_id) ? (string)$doc->user_id : null,
            'idEmploye'       => isset($doc->idEmploye) ? (string)$doc->idEmploye : null,
            'chauffeur_id'    => $doc->chauffeur_id ?? null,
            'pseudo'          => $doc->pseudo ?? null,
        ]);
    }



    public function toDocument(): array
    {
        $doc = [
            'commentaire'     => $this->commentaire,
            'note'            => $this->note,
            'statut'          => $this->statut ?? 'en_attente',
            'created_at'      => $this->datePublication instanceof UTCDateTime
                ? $this->datePublication
                : new UTCDateTime(),
            'user_id'         => $this->idUtilisateurs ? new ObjectId($this->idUtilisateurs) : null,
            'idEmploye'       => $this->idEmploye ? new ObjectId($this->idEmploye) : null,
            'chauffeur_id'    => $this->chauffeurId ? new ObjectId($this->chauffeurId) : null,
            'pseudo'          => $this->pseudo,
        ];

        if ($this->id) {
            $doc['_id'] = new ObjectId($this->id);
        }

        return $doc;
    }
}
