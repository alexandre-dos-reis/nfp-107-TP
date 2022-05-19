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


}
