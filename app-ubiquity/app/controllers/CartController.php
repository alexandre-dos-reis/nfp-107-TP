<?php

namespace controllers;

use App\Services\Cart\CartService;

/**
 * Controller CartController
 */
class CartController extends ControllerBase
{
  /**
   * @autowired
   * @var App\Services\Cart\CartService;
   */
  private CartService $cartService;

  /**
   * @route("/cart", "name"=>"cart")
   * @param App\Services\Cart\CartService;
   */
  public function index(CartService $cartService)
  {
    return $this->loadView('cart/index.html.twig', [
      'cartItems' => $cartService->getDetailedCartItems()
    ]);
  }
}
