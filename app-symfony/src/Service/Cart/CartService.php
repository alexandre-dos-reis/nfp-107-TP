<?php

namespace App\Service\Cart;

use App\Entity\Product;
use App\Service\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }

    public function incrementProductQty(Product $product): bool
    {
        $id = $product->getId();
        $cart = $this->session->get('cart', []);
        $stock = $this->productRepo->find($id)->getStock();

        // Enregistrement
        if (array_key_exists($id, $cart)) {
            $isNewProduct = false;
            $cart[$id] = $cart[$id] + 1;
        } else {
            $isNewProduct = true;
            $cart[$id] = 1;
        }

        if ($cart[$id] > $stock) $cart[$id] = $stock;

        $this->session->set('cart', $cart);
        return $isNewProduct;
    }

    public function updateProductQty(Product $product, int $qty): bool
    {
        $id = $product->getId();
        $cart = $this->session->get('cart', []);
        $stock = $this->productRepo->find($id)->getStock();

        if ($qty <= 0) {
            $isNewQtyNull = true;
            $this->removeProduct($product);
        } else {
            $isNewQtyNull = false;
            $cart[$id] = $qty;
            if ($cart[$id] > $stock) $cart[$id] = $stock;
            $this->session->set('cart', $cart);
        }
        return $isNewQtyNull;
    }

    public function removeProduct(Product $product): void
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$product->getId()]);
        $this->session->set('cart', $cart);
    }

    public function emptyCart(): void
    {
        $this->session->set('cart', []);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepo->find($id);

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

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepo->find($id);

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
