<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoriqueRepository")
 */
class Historique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre", inversedBy="historiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", inversedBy="historiques")
     */
    private $media;

    /**
     * @ORM\Column(type="datetime")
     */
    private $emprunt_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $retour_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $relance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getEmpruntAt(): ?\DateTimeInterface
    {
        return $this->emprunt_at;
    }

    public function setEmpruntAt(\DateTimeInterface $emprunt_at): self
    {
        $this->emprunt_at = $emprunt_at;

        return $this;
    }

    public function getRetourAt(): ?\DateTimeInterface
    {
        return $this->retour_at;
    }

    public function setRetourAt($retour_at = null): self
    {
        $this->retour_at = $retour_at;

        return $this;
    }

    public function getRelance(): ?int
    {
        return $this->relance;
    }

    public function setRelance(?int $relance): self
    {
        $this->relance = $relance;

        return $this;
    }
}
