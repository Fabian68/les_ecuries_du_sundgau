<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreneauxBenevolesRepository")
 */
class CreneauxBenevoles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbBenevoles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="creneauxBenevoles")
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="benevolat")
     */
    private $utilisateurs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texte;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        $date = new \DateTime(($this->getDateDebut())->format('Y-m-d H:i:s'));
        $date = date_sub($date,date_interval_create_from_date_string('1 days'));
        $date->setTime(12,00,00);
        return $date;
    }

    public function getCreneauxFormatted()
    {
        $nbBenevoleRestant = $this->nbBenevoles - $this->utilisateurs->count();
        $string = $this->texte . PHP_EOL . 'Le ' . $this->dateDebut->format( 'd/m/Y' ) . ' de ' . $this->dateDebut->format( 'H:i' ) .' à '. $this->dateFin->format( 'H:i' ) . ' reste ' . $nbBenevoleRestant . ' places ';
        return $string;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->addBenevolat($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->removeElement($utilisateur);
            $utilisateur->removeBenevolat($this);
        }

        return $this;
    }

    public function getNbBenevoles(): ?int
    {
        return $this->nbBenevoles;
    }

    public function setNbBenevoles(int $nbBenevoles): self
    {
        $this->nbBenevoles = $nbBenevoles;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

}
