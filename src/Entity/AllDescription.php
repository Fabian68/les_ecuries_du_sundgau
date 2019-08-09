<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DescriptionRepository")
 * @Vich\Uploadable
 */
class AllDescription
{
    /**
     * 
     */
    private $description = array();

    /**
     * @return array|Description[]
     */
    public function getDescription(): Array
    {
        return $this->description;
    }

    public function addDescription(Description $description): self
    {
        if (!in_array($description, $this->description)) {
            $this->description[] = $description;
        }

        return $this;
    }

}