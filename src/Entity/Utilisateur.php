<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
//use Symfony\Component\Security\Core\User\UserInterface

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"access_control"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get"={"access_control"="is_granted('ROLE_USER') and object.owner == user"},
 *         "put"={"access_control"="is_granted('ROLE_USER') and previous_object.owner == user"},
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(
 *  fields= {"email"},
 *  message= "L'email que vous avez indiqué est déja utilisé."
 * )
 * @Vich\Uploadable
 */
class Utilisateur implements UserInterface
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
     * @Assert\Email()
     */
    private $email;

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

     /**
     * @Groups("read")
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8",minMessage="Votre mot de passe doit faire au moins 8 caractères")
     */
    private $motDePasse;

    /**
     * @Groups("read")
     * @Assert\EqualTo(propertyPath="motDePasse",message="Votre mot de passe doit être le même en confirmation")
     */
    public $confirm_motDePasse;

    public $nouveau_motDePasse;

    public $confirm_nouveauMotDePasse;

    public $confirm_oldMotDePasse;

    /**
     * @Groups("read")
     * @ORM\ManyToOne(targetEntity="App\Entity\Galops", inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $galop;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Repas", mappedBy="cuisine")
     */
    private $repas;

      /**
     * @Groups("read")
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="benevolesMatin")
     */
    private $eventBenevolesMatin;

     /**
     * @Groups("read")
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="benevolesApresMidi")
     */
    private $eventBenevolesApresMidi;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", inversedBy="utilisateurs")
     */
    private $participe;

    /**
     * @Groups("read")
     * @ORM\Column(type="datetime")
     */
    private $dateNaissance;

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    /**
     * @Assert\Image
     * 
     * @Vich\UploadableField(mapping="property_image_profile", fileNameProperty="imageName")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }
 
    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

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

    /**
     * Merci symfony!!!
     */
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
    * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
    */
   public function setImageFile(?File $imageFile = null): void
   {
       $this->imageFile = $imageFile;

       if (null !== $imageFile) {
           // It is required that at least one field changes if you are using doctrine
           // otherwise the event listeners won't be called and the file is lost
           $this->updatedAt = new \DateTime();
       }
   }

   public function getImageFile(): ?File
   {
       return $this->imageFile;
   }

   public function setImageName(?string $imageName): void
   {
       $this->imageName = $imageName;
   }

   public function getImageName(): ?string
   {
       return $this->imageName;
   }

   public function getUpdatedAt(): ?\DateTimeInterface
   {
       return $this->updatedAt;
   }

   public function setUpdatedAt(\DateTimeInterface $updatedAt): self
   {
       $this->updatedAt = $updatedAt;

       return $this;
   }

}
