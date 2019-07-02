<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Symfony\Component\Security\Core\User\UserInterface

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(
 *  fields= {"email"},
 *  message= "L'email que vous avez indiqué est déja utilisé."
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

     /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8",minMessage="Votre mot de passe doit faire au moins 8 caractères")
     */
    private $motDePasse;

    /**
     * @Assert\EqualTo(propertyPath="motDePasse",message="Votre mot de passe doit être le même en confirmation")
     */
    public $confirm_motDePasse;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Galops", inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $galop;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Repas", mappedBy="cuisine")
     */
    private $repas;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", inversedBy="utilisateurs")
     */
    private $participe;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AttributMoyenPaiements", mappedBy="utilisateurs")
     */
    private $attributMoyenPaiements;

    public function __construct()
    {
        $this->repas = new ArrayCollection();
        $this->participe = new ArrayCollection();
        $this->attributMoyenPaiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getUsername() : ?string 
    {
        return $this->getNom() ." ". $this->getPrenom();
    }

    public function getPassword(): ?string 
    {
        return $this->getMotDePasse();
    }

    public function eraseCredentials() {

    }

    public function getSalt() {

    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getGalop(): ?Galops
    {
        return $this->galop;
    }

    public function setGalop(?Galops $galop): self
    {
        $this->galop = $galop;

        return $this;
    }

    /**
     * @return Collection|Repas[]
     */
    public function getRepas(): Collection
    {
        return $this->repas;
    }

    public function addRepa(Repas $repa): self
    {
        if (!$this->repas->contains($repa)) {
            $this->repas[] = $repa;
            $repa->addCuisine($this);
        }

        return $this;
    }

    public function removeRepa(Repas $repa): self
    {
        if ($this->repas->contains($repa)) {
            $this->repas->removeElement($repa);
            $repa->removeCuisine($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getParticipe(): Collection
    {
        return $this->participe;
    }

    public function addParticipe(Event $participe): self
    {
        if (!$this->participe->contains($participe)) {
            $this->participe[] = $participe;
        }

        return $this;
    }

    public function removeParticipe(Event $participe): self
    {
        if ($this->participe->contains($participe)) {
            $this->participe->removeElement($participe);
        }

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|AttributMoyenPaiements[]
     */
    public function getAttributMoyenPaiements(): Collection
    {
        return $this->attributMoyenPaiements;
    }

    public function addAttributMoyenPaiement(AttributMoyenPaiements $attributMoyenPaiement): self
    {
        if (!$this->attributMoyenPaiements->contains($attributMoyenPaiement)) {
            $this->attributMoyenPaiements[] = $attributMoyenPaiement;
            $attributMoyenPaiement->addUtilisateur($this);
        }

        return $this;
    }

    public function removeAttributMoyenPaiement(AttributMoyenPaiements $attributMoyenPaiement): self
    {
        if ($this->attributMoyenPaiements->contains($attributMoyenPaiement)) {
            $this->attributMoyenPaiements->removeElement($attributMoyenPaiement);
            $attributMoyenPaiement->removeUtilisateur($this);
        }

        return $this;
    }


}
