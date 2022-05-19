<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Employee;
use App\Entity\Timeslot;
use App\Entity\User;
use Exception;

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

    private const STATUSES = [
        0 => 'CREATED',
        1 => 'PREPARED',
        2 => 'CANCELED'
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Employee
     *
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEmployee", referencedColumnName="id")
     * })
     */
    private $employee;

    /**
     * @var Timeslot
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getStatusKey(): ?int
    {
        return 0; // TODOS
    }

    public function getStatusLabel(): ?int
    {
        return self::STATUSES[$this->status];
    }

    public function setStatus(int $status): self
    {
        if (array_key_exists($status, self::STATUSES)) {
            $this->status = $status;
            return $this;
        } else {
            throw new Exception("This status doesn't exists !");
        }
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getToPay(): ?int
    {
        return $this->toPay;
    }

    public function setToPay(int $toPay): self
    {
        $this->toPay = $toPay;

        return $this;
    }

    public function getItemsNumber(): ?int
    {
        return $this->itemsNumber;
    }

    public function setItemsNumber(int $itemsNumber): self
    {
        $this->itemsNumber = $itemsNumber;

        return $this;
    }

    public function getMissingNumber(): ?int
    {
        return $this->missingNumber;
    }

    public function setMissingNumber(int $missingNumber): self
    {
        $this->missingNumber = $missingNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getTimeSlot(): ?Timeslot
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(?Timeslot $timeSlot): self
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
