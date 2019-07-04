<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* @ApiResource(normalizationContext={"groups"={"read"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FilesPdfRepository")
 * @Vich\Uploadable
 */
class FilesPdf
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

     /**
     * @Assert\File(
     * maxSize = "10024k",
     * mimeTypes = {"application/pdf", "application/x-pdf"},
     * mimeTypesMessage = "L'upload ne prend que les fichiers PDF"
     * )
     * 
     * @Vich\UploadableField(mapping="property_pdf", fileNameProperty="fileName")
     * 
     * @var File|null
     */
    private $pdfFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
    * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $pdfFile
    */
   public function setPdfFile(?File $pdfFile = null): void
   {
       $this->pdfFile = $pdfFile;

       if (null !== $pdfFile) {
           // It is required that at least one field changes if you are using doctrine
           // otherwise the event listeners won't be called and the file is lost
           $this->updatedAt = new \DateTime();
       }
   }

   public function getPdfFile(): ?File
   {
       return $this->pdfFile;
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

    public function __construct()
    {
        $this->filesPdf = new EmbeddedFile();
    }
}
