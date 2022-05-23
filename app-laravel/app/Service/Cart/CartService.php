<?php

namespace App\Service\Cart;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Service\Cart\CartItem;

class CartService
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function getCart(): array
    {
        return $this->request->session()->get('cart', []);
    }

    private function setCart(array $cart): void
    {
        $this->request->session()->put('cart', $cart);
    }

    public function incrementProductQty(Product $product): bool
    {
        $id = $product->id;
        $stock = $product->stock;
        $cart = $this->getCart();

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
        $id = $product->id;
        $stock = $product->stock;
        $cart = $this->getCart();

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
        unset($cart[$product->id]);
        $this->setCart($cart);
    }

    public function emptyCart(): void
    {
        $this->setCart([]);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getDetailedCartItems() as $cartItem) {
            $total += $cartItem->product->price * $cartItem->qty;
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

            /**
             * @var Product $product
             */
            $product = Product::find($id);

            if (!$product) {
                continue;
            }

            // Vérifier que le stock demandé ne dépasse pas le stock disponible.
            if ($qty > $product->stock) $qty = $product->stock;

            $detailedCard[] = new CartItem($product, $qty);
        }

        return $detailedCard;
    }

    public function countProducts()
    {
        $total = 0;
        foreach ($this->getDetailedCartItems() as $cartItem) {
            $total += $cartItem->qty;
        }
        return $total;
    }

    public function isEmpty(): bool
    {
        return $this->countProducts() === 0 ? true : false;
    }
}
