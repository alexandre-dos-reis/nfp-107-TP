<?php

namespace App\Service\Cart;

use App\Models\Product;


class CartItem
{
    public Product $product;

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
