<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="comments", columns={"comments"}), @ORM\Index(name="idSection", columns={"idSection"}), @ORM\Index(name="name", columns={"name"})})
 * @ORM\Entity
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comments", type="text", length=65535, nullable=true)
     */
    private $comments;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    private $stock;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", length=65535, nullable=true)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="promotion", type="integer", nullable=false)
     */
    private $promotion;

    /**
     * @var \Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSection", referencedColumnName="id")
     * })
     */
    private $section;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="idassoproduct")
     */
    private $products;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Basket", mappedBy="idproduct")
     */
    private $baskets;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Order", mappedBy="idproduct")
     */
    private $orders;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="idproduct")
     * @ORM\JoinTable(name="pack",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idProduct", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idPack", referencedColumnName="id")
     *   }
     * )
     */
    private $packs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->baskets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->packs = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
