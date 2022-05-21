<?php

namespace App\Service\Cart;

use App\Entity\Product;

class CartItem
{
    public product $product;

    public int $qty;

    public function __construct(Product $product, int $qty)
    {
        $this->product = $product;
        $this->qty = $qty;
    }

    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->qty;
    }
}
