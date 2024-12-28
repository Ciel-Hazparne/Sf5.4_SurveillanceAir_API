<?php

namespace App\Entity;

use App\Repository\CO2Repository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CO2Repository::class)
 * @ApiResource()
 */
class CO2
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $data;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?float
    {
        return $this->data;
    }

    public function setData(float $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __toString()
    {
        return $this->getDate()->format("d/m H:i");
    }

}
