<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
* @ApiResource(normalizationContext={"groups"={"read"}})
 * @UniqueEntity(
 *  fields= {"Libelle"} 
 * )
* @ORM\Entity(repositoryClass="App\Repository\AttributMoyenPaiementsRepository")
*/
class AttributMoyenPaiements
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
    private $Libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UtilisateurMoyenPaiementEvent", mappedBy="attributMoyenPaiement")
     */
    private $utilisateurMoyenPaiementEvents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UtilisateurMoyenPaiementEvent", mappedBy="attributMoyenPaiements")
     */
    private $utilisateurMoyenPaiementEvent;

   

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->utilisateurMoyenPaiementEvents = new ArrayCollection();
        $this->utilisateurMoyenPaiementEvent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    /**
     * @return Collection|UtilisateurMoyenPaiementEvent[]
     */
    public function getUtilisateurMoyenPaiementEvents(): Collection
    {
        return $this->utilisateurMoyenPaiementEvents;
    }

    public function addUtilisateurMoyenPaiementEvent(UtilisateurMoyenPaiementEvent $utilisateurMoyenPaiementEvent): self
    {
        if (!$this->utilisateurMoyenPaiementEvents->contains($utilisateurMoyenPaiementEvent)) {
            $this->utilisateurMoyenPaiementEvents[] = $utilisateurMoyenPaiementEvent;
            $utilisateurMoyenPaiementEvent->setAttributMoyenPaiement($this);
        }

        return $this;
    }

    public function removeUtilisateurMoyenPaiementEvent(UtilisateurMoyenPaiementEvent $utilisateurMoyenPaiementEvent): self
    {
        if ($this->utilisateurMoyenPaiementEvents->contains($utilisateurMoyenPaiementEvent)) {
            $this->utilisateurMoyenPaiementEvents->removeElement($utilisateurMoyenPaiementEvent);
            // set the owning side to null (unless already changed)
            if ($utilisateurMoyenPaiementEvent->getAttributMoyenPaiement() === $this) {
                $utilisateurMoyenPaiementEvent->setAttributMoyenPaiement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UtilisateurMoyenPaiementEvent[]
     */
    public function getUtilisateurMoyenPaiementEvent(): Collection
    {
        return $this->utilisateurMoyenPaiementEvent;
    }

}
