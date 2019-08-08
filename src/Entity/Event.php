<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
* @ApiResource(normalizationContext={"groups"={"read"} })
* @ApiFilter(OrderFilter::class, properties={"id", "dates"}, arguments={"orderParameterName"="order"})
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
    private $tarifPlusDe12;

    /**
     * @Groups("read")
     * @ORM\Column(type="float")
     */
    private $tarifProprietaire;

    /**
     * @Groups("read")
     * @ORM\Column(type="integer")
     */
    private $nbMaxParticipants;

    /**
     * @Groups("read")
     * @ORM\OneToMany(targetEntity="App\Entity\DatesEvenements", mappedBy="event", orphanRemoval=true,cascade={"persist", "remove"})
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
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="evenement",orphanRemoval=true,cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CreneauxBenevoles", mappedBy="event", orphanRemoval=true,cascade={"persist", "remove"})
     */
    private $creneauxBenevoles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", mappedBy="mange")
     */
    private $utilisateursMange;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repasPossible;

    /**
     * @Groups("read")
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $divers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="evenement",cascade={"persist", "remove"})
     */
    private $videos;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\UtilisateurMoyenPaiementEvent", mappedBy="event")
     */
    private $utilisateurMoyenPaiementEvents;

    private $choixPrix=0;

    private $signataire='';

    /**
     * @var \DateTime
     */
    private $dateDivers;

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
        $this->videos = new ArrayCollection();
        $this->tarifMoinsDe12=0.0;
        $this->tarifPlusDe12=0.0;
        $this->tarifProprietaire=0.0;
        $this->nbMaxParticipants=0;
        $this->dateDivers= new \DateTime('now');
    }

    function __clone()
    {
        $this->images = clone $this->images;
        $this->videos = clone $this->videos;
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

    public function getTarifPlusDe12(): ?float
    {
        return $this->tarifPlusDe12;
    }

    public function setTarifPlusDe12(float $tarifPlusDe12): self
    {
        $this->tarifPlusDe12 = $tarifPlusDe12;

        return $this;
    }

    public function getTarifProprietaire(): ?float
    {
        return $this->tarifProprietaire;
    }

    public function setTarifProprietaire(float $tarifProprietaire): self
    {
        $this->tarifProprietaire = $tarifProprietaire;

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

    public function getRepasPossible(): ?bool
    {
        return $this->repasPossible;
    }

    public function setRepasPossible(?bool $repasPossible): self
    {
        $this->repasPossible = $repasPossible;

        return $this;
    }

    public function getDivers(): ?bool
    {
        return $this->divers;
    }

    public function setDivers(?bool $divers): self
    {
        $this->divers = $divers;

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setEvenement($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getEvenement() === $this) {
                $video->setEvenement(null);
            }
        }

        return $this;
    }

    public function getChoixPrix(): ?int
    {
        return $this->choixPrix;
    }

    public function setChoixPrix(int $choixPrix): self
    {
        $this->choixPrix = $choixPrix;

        return $this;
    }

    public function getSignataire(): ?string
    {
        return $this->signataire;
    }

    public function setSignataire(string $signataire): self
    {
        $this->signataire = $signataire;
        return $this;
    }

    public function getDateDivers()
    {
        return $this->dateDivers;
    }

    public function setDateDivers($dateDivers)
    {
        $this->dateDivers = $dateDivers;
        return $this;
    }
    
    public function getAttributMoyenPaiement(): ?AttributMoyenPaiements
    {
        return $this->attributMoyenPaiement;
    }

    public function setAttributMoyenPaiement(?AttributMoyenPaiements $attributMoyenPaiement): self
    {
        $this->attributMoyenPaiement = $attributMoyenPaiement;

        return $this;
    }

}
