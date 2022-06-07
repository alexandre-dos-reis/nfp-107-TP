<?php

namespace App\Service\Cart;

use App\Entity\Product;
use App\Service\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected SessionInterface $session;
    protected ProductRepository $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }

    public function incrementProductQty(Product $product): bool
    {
        $id = $product->getId();
        $cart = $this->getCart();

        // Enregistrement
        if (array_key_exists($id, $cart)) {
            $isNewProduct = false;
            $cart[$id] = $cart[$id] + 1;
        } else {
            $isNewProduct = true;
            $cart[$id] = 1;
        }

        $this->setCart(self::capStock($product, $cart));

        if ($isNewProduct) {
            $this->setTotal($this->getTotal() + ($cart[$id] * $product->getPrice()));
        } else {
            $this->setTotal($this->getTotal() + ($cart[$id] - 1) * $product->getPrice());
        }

        $this->setCount();

        return $isNewProduct;
    }

    /**
     * @param int[] $cart
     * @return int[]
     */
    public static function capStock(Product $product, array $cart): array
    {
        $currentStock = $product->getStock();
        if ($cart[$product->getId()] > $currentStock) {
            $cart[$product->getId()] = $currentStock;
        }
        return $cart;
    }

    public function updateProductQty(Product $product, int $newQty): bool
    {
        if ($newQty <= 0) {
            $isNewQtyNull = true;
            $this->removeProduct($product);
        } else {
            $isNewQtyNull = false;
            $cart = $this->getCart();

            $oldQty = $cart[$product->getId()];
            $cart[$product->getId()] = $newQty;

            $this->setCart(self::capStock($product, $cart));
            $this->setTotal($this->getTotal() + ($newQty - $oldQty) * $product->getPrice());
            $this->setCount();
        }
        return $isNewQtyNull;
    }

    public function removeProduct(Product $product): void
    {
        $cart = $this->getCart();

        $qty = $cart[$product->getId()];
        $amountToSubstract = $qty * $product->getPrice();
        unset($cart[$product->getId()]);
        $this->setCart($cart);
        $this->setTotal($this->getTotal() - $amountToSubstract);
        $this->setCount();
    }

    public function emptyCart(): void
    {
        $this->setCart([]);
        $this->setTotal(0);
        $this->setCount(0);
    }

    public function getTotal(): int
    {
        return $this->session->get('totalCart', 0);
    }

    public function setTotal(int $total): void
    {
        $this->session->set('totalCart', $total);
    }

    public function getCount(): int
    {
        return $this->session->get('countCart', 0);
    }

    /**
     * @param int[] $cart
     */
    public function setCount(): void
    {
        $this->session->set('countCart', array_sum($this->getCart()));
    }

    /**
     * @return int[] $cart
     */
    private function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    /**
     * @param int[] $cart
     */
    private function setCart(array $cart)
    {
        return $this->session->set('cart', $cart);
    }

    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        if ($this->isEmpty()) return [];

        $cart = $this->getCart();
        $detailedCard = [];
        $currentProducts = $this->productRepo->findBy(['id' => array_keys($cart)]);

        foreach ($currentProducts as $p) {
            $detailedCard[] = new CartItem($p, $cart[$p->getId()]);
        }

        return $detailedCard;
    }

    public function isEmpty(): bool
    {
        return $this->getCount() === 0;
    }
}
