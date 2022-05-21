<?php

namespace App\Entity;

use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PurchasedetailRepository;

/**
 * @ORM\Entity(repositoryClass=PurchasedetailRepository::class)
 */
class Purchasedetail
{
    /**
     * @ORM\Table(name="purchasedetail", indexes={@ORM\Index(name="idOrder", columns={"idOrder"}), @ORM\Index(name="idProduct", columns={"idProduct"})})
     * @ORM\Entity(repositoryClass="App\Repository\PurchasedetailRepository")
     */

    /**
     * @var int
     *
     * @ORM\Column(name="idProduct", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduct;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prepared", type="integer", nullable=false)
     */
    private $prepared;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProduct", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var Purchase
     *
     * @ORM\ManyToOne(targetEntity="Purchase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idOrder", referencedColumnName="id")
     * })
     */
    private $purchase;

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrepared(): ?int
    {
        return $this->prepared;
    }

    public function setPrepared(int $prepared): self
    {
        $this->prepared = $prepared;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }
}
