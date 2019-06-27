<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $brochure;

    public function getBrochure(): ?string
    {
        return $this->brochure;
    }

    public function setBrochure(string $brochure): self
    {
        $this->brochure = $brochure;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

}
