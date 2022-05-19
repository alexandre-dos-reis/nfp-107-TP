<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="order", indexes={@ORM\Index(name="idEmployee", columns={"idEmployee"}), @ORM\Index(name="idTimeslot", columns={"idTimeslot"}), @ORM\Index(name="idUser", columns={"idUser"})})
 * @ORM\Entity
 */
class Order
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
     * @ORM\Column(name="dateCreation", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datecreation = 'CURRENT_TIMESTAMP';

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="toPay", type="integer", nullable=false)
     */
    private $toPay;

    /**
     * @var int
     *
     * @ORM\Column(name="itemsNumber", type="integer", nullable=false)
     */
    private $itemsNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="missingNumber", type="integer", nullable=false)
     */
    private $missingNumber;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Employee
     *
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEmployee", referencedColumnName="id")
     * })
     */
    private $employee;

    /**
     * @var \Timeslot
     *
     * @ORM\ManyToOne(targetEntity="Timeslot")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTimeslot", referencedColumnName="id")
     * })
     */
    private $timeSlot;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="idorder")
     * @ORM\JoinTable(name="orderdetail",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idOrder", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idProduct", referencedColumnName="id")
     *   }
     * )
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
