<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slot
 *
 * @ORM\Table(name="slot")
 * @ORM\Entity
 */
class Slot
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="name", type="time", nullable=false)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="days", type="json", nullable=false)
     */
    private $days;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?\DateTimeInterface
    {
        return $this->name;
    }

    public function setName(\DateTimeInterface $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDays(): ?array
    {
        return $this->days;
    }

    public function setDays(array $days): self
    {
        $this->days = $days;

        return $this;
    }


}
