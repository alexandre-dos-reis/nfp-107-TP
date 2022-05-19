<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timeslot
 *
 * @ORM\Table(name="timeslot", uniqueConstraints={@ORM\UniqueConstraint(name="slotDate", columns={"slotDate"})})
 * @ORM\Entity
 */
class Timeslot
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
     * @ORM\Column(name="slotDate", type="datetime", nullable=false)
     */
    private $slotdate;

    /**
     * @var bool
     *
     * @ORM\Column(name="full", type="boolean", nullable=false)
     */
    private $full = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="expired", type="boolean", nullable=false)
     */
    private $expired = false;


}
