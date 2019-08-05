<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DescriptionRepository")
 * @Vich\Uploadable
 */
class Description
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $texte;

/**
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 1000,
     *     minHeight = 100,
     *     maxHeight = 1000,
     *     minRatio = 0.6,
     *     maxRatio = 2
     * )
     * 
     * @Vich\UploadableField(mapping="property_image_desc", fileNameProperty="imageName")
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

    public function getId(): ?int
    {
        return $this->id;
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
