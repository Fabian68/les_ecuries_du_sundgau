<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ApiResource(normalizationContext={"groups"={"read"} })
* @ORM\Entity(repositoryClass="App\Repository\DatesEvenementsRepository")
*/
class DatesEvenements
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
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @Groups("read")
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="dateDebut",message="Cet date doit être superieur à la première date")
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="dates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        $date = new \DateTime(($this->getDateDebut())->format('Y-m-d H:i:s'));
        $date = date_sub($date,date_interval_create_from_date_string('1 days'));
        $date->setTime(12,00,00);
        return $date;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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
