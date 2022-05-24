<?php

namespace App\Http\Controllers;

use Faker\Factory;
use App\Models\User;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Purchasedetail;
use App\Models\Timeslot;
use App\Service\Cart\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function index(CartService $cartService)
    {
        if ($cartService->countProducts() === 0) {
            return redirect()
                ->route('cart_index')
                ->with('error', "You cart is empty !");
        }

        return view('payment/index', [
            'cartItems' => $cartService->getDetailedCartItems(),
            'total' => $cartService->getTotal()
        ]);
    }

    public function pay(CartService $cs): RedirectResponse
    {
        DB::transaction(function () use ($cs) {

            if ($cs->countProducts() === 0) {
                return redirect()
                    ->route('cart_index')
                    ->with('error', "You cart is empty !");
            }

            $faker = Factory::create();

            $p = new Purchase();

            $p->amount = $cs->getTotal();
            $p->dateCreation = new \DateTime();
            $p->itemsNumber = $cs->countProducts();
            $p->missingNumber = 0;
            $p->status = Purchase::STATUS_CREATED;
            $p->toPay = 0;
            $p->idUser = $faker->randomElement(User::all())->id;
            $p->idEmployee = $faker->randomElement(Employee::all())->id;
            $p->idTimeslot = $faker->randomElement(Timeslot::all())->id;

            $p->save();

            foreach ($cs->getDetailedCartItems() as $ci) {

                /**
                 * @var Product $product
                 */
                $product = Product::findOrFail($ci->product->id);
                $newStock = $product->stock - $ci->qty;

                if ($newStock < 0) {
                    redirect()
                        ->route('cart_index')
                        ->with("The quantity requested for the #{$product->id} {$product->name} exceeds the available stock !");
                }

                $product->stock = $newStock;
                $product->save();

                $pd = new Purchasedetail();
                $pd->idProduct = $ci->product->id;
                $pd->idOrder = $p->id;
                $pd->quantity = $ci->qty;
                $pd->prepared = 0;

                $pd->save();
            }
        });

        $cs->emptyCart();

        return redirect()
            ->route('cart_index')
            ->with('success', "Your payment has succeded, we are preparing your order.");
    }
}
