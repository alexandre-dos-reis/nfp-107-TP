<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Service\Cart\CartService;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function index(CartService $cartService): View
    {
        return view('cart/index', [
            'cartItems' => $cartService->getDetailedCartItems(),
            'total' => $cartService->getTotal()
        ]);
    }

    public function removeProduct(int $id, CartService $cartService): RedirectResponse
    {

        $product = Product::findOrFail($id);
        $cartService->removeProduct($product);

        return redirect()
            ->route('cart_index')
            ->with('success', "The {$product->name} has been removed from your cart.");
    }

    public function updateQty(int $id, CartService $cartService, Request $request): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $newQty = $request->input('qty-' . $id);

        if (is_null($newQty)) return redirect()
            ->route('cart_index')
            ->with('error', "The quantity cannot be null !");

        $isNewQtyNull = $cartService->updateProductQty($product, (int)$newQty);

        if ($isNewQtyNull) return redirect()
            ->route('cart_index')
            ->with('success', "The {$product->name} has been removed from your cart.");

        return redirect()
            ->route('cart_index')
            ->with('success', "The quantity for the {$product->name} has been updated.");
    }

    public function incrementQty(int $id, CartService $cartService): RedirectResponse
    {
        $product = Product::find($id);
        $cartService->incrementProductQty($product);

        return redirect()
            ->route('cart_index')
            ->with('success', "The {$product->name} has been added to your cart.");
    }
}
