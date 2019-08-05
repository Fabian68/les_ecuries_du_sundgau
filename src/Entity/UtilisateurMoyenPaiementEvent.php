<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurMoyenPaiementEventRepository")
 */
class UtilisateurMoyenPaiementEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="utilisateurMoyenPaiementEvents")
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AttributMoyenPaiements", inversedBy="utilisateurMoyenPaiementEvents")
     */
    private $attributMoyenPaiement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="utilisateurMoyenPaiementEvents")
     */
    private $event;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
