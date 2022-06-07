<?php

namespace App\Services\Cart;

use models\Product;
use Ubiquity\orm\DAO;
use App\Service\Cart\CartItem;
use Ubiquity\utils\http\USession;

class CartService
{
    /**
     * @return int[]
     */
    private function getCart(): array
    {
        return USession::get('cart', []);
    }

    /**
     * @param int[] $cart
     */
    private function setCart(array $cart): void
    {
        USession::set('cart', []);
    }

    public function incrementProductQty(Product $product): bool
    {
        $id = $product->getId();
        $cart = $this->getCart();
        $stock = DAO::getOne(Product::class, $product->getId())->getStock();

        // Enregistrement
        if (array_key_exists($id, $cart)) {
            $isNewProduct = false;
            $cart[$id] = $cart[$id] + 1;
        } else {
            $isNewProduct = true;
            $cart[$id] = 1;
        }

        if ($cart[$id] > $stock) $cart[$id] = $stock;

        $this->setCart($cart);
        return $isNewProduct;
    }

    public function updateProductQty(Product $product, int $qty): bool
    {
        $id = $product->getId();
        $cart = $this->getCart();
        $stock = DAO::getOne(Product::class, $product->getId())->getStock();

        if ($qty <= 0) {
            $isNewQtyNull = true;
            $this->removeProduct($product);
        } else {
            $isNewQtyNull = false;
            $cart[$id] = $qty;
            if ($cart[$id] > $stock) $cart[$id] = $stock;
            $this->setCart($cart);
        }
        return $isNewQtyNull;
    }

    public function removeProduct(Product $product): void
    {
        $cart = $this->getCart();
        unset($cart[$product->getId()]);
        $this->setCart($cart);
    }

    public function emptyCart(): void
    {
        $this->setCart([]);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {
            $product = DAO::getOne(Product::class, $id);

            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() * $qty);
        }

        return $total;
    }


    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCard = [];

        foreach ($this->getCart() as $id => $qty) {
            $product = DAO::getOne(Product::class, $id);

            if (!$product) {
                continue;
            }

            // Vérifier que le stock demandé ne dépasse pas le stock disponible.
            if ($qty > $product->getStock()) {
                $qty = $product->getStock();
            }

            $detailedCard[] = new CartItem($product, $qty);
        }

        return $detailedCard;
    }

    public function countProducts(): int
    {
        return count($this->getDetailedCartItems());
    }

    public function isEmpty(): bool
    {
        return $this->countProducts() === 0 ? true : false;
    }
}
