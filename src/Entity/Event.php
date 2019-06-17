<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @ORM\Column(type="integer")
     */
    private $tarifMoinsDe12;

    /**
     * @ORM\Column(type="integer")
     */
    private $plusDe12;

    /**
     * @ORM\Column(type="integer")
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbMaxParticipants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DatesEvenements", mappedBy="event")
     */
    private $dates;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Galops", inversedBy="evenements")
     */
    private $galops;

    public function __construct()
    {
        $this->dates = new ArrayCollection();
        $this->galops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getTarifMoinsDe12(): ?int
    {
        return $this->tarifMoinsDe12;
    }

    public function setTarifMoinsDe12(int $tarifMoinsDe12): self
    {
        $this->tarifMoinsDe12 = $tarifMoinsDe12;

        return $this;
    }

    public function getPlusDe12(): ?int
    {
        return $this->plusDe12;
    }

    public function setPlusDe12(int $plusDe12): self
    {
        $this->plusDe12 = $plusDe12;

        return $this;
    }

    public function getProprietaire(): ?int
    {
        return $this->proprietaire;
    }

    public function setProprietaire(int $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getNbMaxParticipants(): ?int
    {
        return $this->nbMaxParticipants;
    }

    public function setNbMaxParticipants(int $nbMaxParticipants): self
    {
        $this->nbMaxParticipants = $nbMaxParticipants;

        return $this;
    }

    /**
     * @return Collection|DatesEvenements[]
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }

    public function addDate(DatesEvenements $date): self
    {
        if (!$this->dates->contains($date)) {
            $this->dates[] = $date;
            $date->setEvent($this);
        }

        return $this;
    }

    public function removeDate(DatesEvenements $date): self
    {
        if ($this->dates->contains($date)) {
            $this->dates->removeElement($date);
            // set the owning side to null (unless already changed)
            if ($date->getEvent() === $this) {
                $date->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Galops[]
     */
    public function getGalops(): Collection
    {
        return $this->galops;
    }

    public function addGalops(Galops $galops): self
    {
        if (!$this->galops->contains($galops)) {
            $this->galops[] = $galops;
        }

        return $this;
    }

    public function removeGalops(Galops $galops): self
    {
        if ($this->relation->contains($galops)) {
            $this->relation->removeElement($galops);
        }

        return $this;
    }
}