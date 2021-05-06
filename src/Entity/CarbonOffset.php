<?php

namespace App\Entity;

use App\Repository\CarbonOffsetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarbonOffsetRepository::class)
 */
class CarbonOffset
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
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $shortEmissionPercentage;

    /**
     * @ORM\Column(type="integer")
     */
    private $shortRemovalPercentage;

    /**
     * @ORM\Column(type="integer")
     */
    private $longEmissionPercentage;

    /**
     * @ORM\Column(type="integer")
     */
    private $longRemovalPercentage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getShortEmissionPercentage(): ?int
    {
        return $this->shortEmissionPercentage;
    }

    public function setShortEmissionPercentage(int $shortEmissionPercentage): self
    {
        $this->shortEmissionPercentage = $shortEmissionPercentage;

        return $this;
    }

    public function getShortRemovalPercentage(): ?int
    {
        return $this->shortRemovalPercentage;
    }

    public function setShortRemovalPercentage(int $shortRemovalPercentage): self
    {
        $this->shortRemovalPercentage = $shortRemovalPercentage;

        return $this;
    }

    public function getLongEmissionPercentage(): ?int
    {
        return $this->longEmissionPercentage;
    }

    public function setLongEmissionPercentage(int $longEmissionPercentage): self
    {
        $this->longEmissionPercentage = $longEmissionPercentage;

        return $this;
    }

    public function getLongRemovalPercentage(): ?int
    {
        return $this->longRemovalPercentage;
    }

    public function setLongRemovalPercentage(int $longRemovalPercentage): self
    {
        $this->longRemovalPercentage = $longRemovalPercentage;

        return $this;
    }
}
