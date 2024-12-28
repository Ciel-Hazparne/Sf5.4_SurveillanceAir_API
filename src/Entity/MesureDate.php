<?php

namespace App\Entity;

use App\Repository\MesureDateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MesureDateRepository::class)
 */
class MesureDate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $min;

    /**
     * @ORM\Column(type="datetime")
     */
    private $max;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMin(): ?\DateTimeInterface
    {
        return $this->min;
    }

    public function setMin(\DateTimeInterface $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?\DateTimeInterface
    {
        return $this->max;
    }

    public function setMax(\DateTimeInterface $max): self
    {
        $this->max = $max;

        return $this;
    }
}
