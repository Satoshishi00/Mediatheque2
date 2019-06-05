<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BibliothequeRepository")
 */
class Bibliotheque
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Etagere", mappedBy="bibliotheque", orphanRemoval=true)
     */
    private $etageres;

    public function __construct()
    {
        $this->etageres = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }
    
    
    /**
     * @return Collection|Etagere[]
     */
    public function getEtageres(): Collection
    {
        return $this->etageres;
    }

    public function addEtagere(Etagere $etagere): self
    {
        if (!$this->etageres->contains($etagere)) {
            $this->etageres[] = $etagere;
            $etagere->setBibliotheque($this);
        }

        return $this;
    }

    public function removeEtagere(Etagere $etagere): self
    {
        if ($this->etageres->contains($etagere)) {
            $this->etageres->removeElement($etagere);
            // set the owning side to null (unless already changed)
            if ($etagere->getBibliotheque() === $this) {
                $etagere->setBibliotheque(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    
}
