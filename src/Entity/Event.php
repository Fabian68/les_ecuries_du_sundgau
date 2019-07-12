<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
* @ApiResource(normalizationContext={"groups"={"read"} })
* @ORM\Entity(repositoryClass="App\Repository\EventRepository")
*/
class Event
{
    /**
     * @Groups("read")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Groups("read")
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @Groups("read")
     * @ORM\Column(type="float")
     */
    private $tarifMoinsDe12;

    /**
     * @Groups("read")
     * @ORM\Column(type="float")
     */
    private $plusDe12;

    /**
     * @Groups("read")
     * @ORM\Column(type="float")
     */
    private $proprietaire;

    /**
     * @Groups("read")
     * @ORM\Column(type="integer")
     */
    private $nbMaxParticipants;

    /**
     * @Groups("read")
     * @ORM\OneToMany(targetEntity="App\Entity\DatesEvenements", mappedBy="event")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dates;

    /**
     * @Groups("read")
     * @ORM\ManyToMany(targetEntity="App\Entity\Galops", inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $galops;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="participe")
     */
    private $utilisateurs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="evenement")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CreneauxBenevoles", mappedBy="event")
     */
    private $creneauxBenevoles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="mange")
     */
    private $utilisateursMange;

    public function __construct()
    {
        $this->dates = new ArrayCollection();
        $this->galops = new ArrayCollection();
        $this->benevoles = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->attributMoyenPaiements = new ArrayCollection();
        $this->creneauxBenevoles = new ArrayCollection();
        $this->utilisateursMange = new ArrayCollection();
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

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getTarifMoinsDe12(): ?float
    {
        return $this->tarifMoinsDe12;
    }

    public function setTarifMoinsDe12(float $tarifMoinsDe12): self
    {
        $this->tarifMoinsDe12 = $tarifMoinsDe12;

        return $this;
    }

    public function getPlusDe12(): ?float
    {
        return $this->plusDe12;
    }

    public function setPlusDe12(float $plusDe12): self
    {
        $this->plusDe12 = $plusDe12;

        return $this;
    }

    public function getProprietaire(): ?float
    {
        return $this->proprietaire;
    }

    public function setProprietaire(float $proprietaire): self
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
            $utilisateur->addParticipe($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->removeElement($utilisateur);
            $utilisateur->removeParticipe($this);
        }

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setEvenement($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getEvenement() === $this) {
                $image->setEvenement(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }

    public function addGalop(Galops $galop): self
    {
        if (!$this->galops->contains($galop)) {
            $this->galops[] = $galop;
        }

        return $this;
    }

    /**
     * @return Collection|CreneauxBenevoles[]
     */
    public function getCreneauxBenevoles(): Collection
    {
        return $this->creneauxBenevoles;
    }

    public function addCreneauxBenevole(CreneauxBenevoles $creneauxBenevole): self
    {
        if (!$this->creneauxBenevoles->contains($creneauxBenevole)) {
            $this->creneauxBenevoles[] = $creneauxBenevole;
            $creneauxBenevole->setEvent($this);
        }

        return $this;
    }

    public function removeCreneauxBenevole(CreneauxBenevoles $creneauxBenevole): self
    {
        if ($this->creneauxBenevoles->contains($creneauxBenevole)) {
            $this->creneauxBenevoles->removeElement($creneauxBenevole);
            // set the owning side to null (unless already changed)
            if ($creneauxBenevole->getEvent() === $this) {
                $creneauxBenevole->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateursMange(): Collection
    {
        return $this->utilisateursMange;
    }

    public function addUtilisateursMange(Utilisateur $utilisateursMange): self
    {
        if (!$this->utilisateursMange->contains($utilisateursMange)) {
            $this->utilisateursMange[] = $utilisateursMange;
            $utilisateursMange->addMange($this);
        }

        return $this;
    }

    public function removeUtilisateursMange(Utilisateur $utilisateursMange): self
    {
        if ($this->utilisateursMange->contains($utilisateursMange)) {
            $this->utilisateursMange->removeElement($utilisateursMange);
            $utilisateursMange->removeMange($this);
        }

        return $this;
    }



}
