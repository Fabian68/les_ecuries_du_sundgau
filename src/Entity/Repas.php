<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepasRepository")
 */
class Repas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreBenevoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="repas")
     */
    private $repasEvent;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="repas")
     * benevoles qui aide a preparer le repas
     */
    private $cuisine;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="repas")
     * @ORM\JoinTable(name="repas_utilisateur_cuisine")
     * client qui paie son repas
     */
    private $mange;

    public function __construct()
    {
        $this->repasEvent = new ArrayCollection();
        $this->cuisine = new ArrayCollection();
        $this->mange = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreBenevoles(): ?int
    {
        return $this->nombreBenevoles;
    }

    public function setNombreBenevoles(int $nombreBenevoles): self
    {
        $this->nombreBenevoles = $nombreBenevoles;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getRepasEvent(): Collection
    {
        return $this->repasEvent;
    }

    public function addRepasEvent(Event $repasEvent): self
    {
        if (!$this->repasEvent->contains($repasEvent)) {
            $this->repasEvent[] = $repasEvent;
            $repasEvent->setRepas($this);
        }

        return $this;
    }

    public function removeRepasEvent(Event $repasEvent): self
    {
        if ($this->repasEvent->contains($repasEvent)) {
            $this->repasEvent->removeElement($repasEvent);
            // set the owning side to null (unless already changed)
            if ($repasEvent->getRepas() === $this) {
                $repasEvent->setRepas(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getCuisine(): Collection
    {
        return $this->cuisine;
    }

    public function addCuisine(Utilisateur $cuisine): self
    {
        if (!$this->cuisine->contains($cuisine)) {
            $this->cuisine[] = $cuisine;
        }

        return $this;
    }

    public function removeCuisine(Utilisateur $cuisine): self
    {
        if ($this->cuisine->contains($cuisine)) {
            $this->cuisine->removeElement($cuisine);
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getMange(): Collection
    {
        return $this->mange;
    }

    public function addMange(Utilisateur $mange): self
    {
        if (!$this->mange->contains($mange)) {
            $this->mange[] = $mange;
        }

        return $this;
    }

    public function removeMange(Utilisateur $mange): self
    {
        if ($this->mange->contains($mange)) {
            $this->mange->removeElement($mange);
        }

        return $this;
    }
}
