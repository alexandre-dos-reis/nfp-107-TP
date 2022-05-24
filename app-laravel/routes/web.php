<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ProductController::class, 'index'])->name('product_index');
Route::get('/products/{id}', [ProductController::class, 'detail'])->name('product_detail');

Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchase_index');
Route::get('/purchases/{id}', [PurchaseController::class, 'detail'])->name('purchase_detail');
Route::post('/purchases/update-status/{id}', [PurchaseController::class, 'updateStatus'])->name('purchase_update_status');

Route::get('/cart', [CartController::class, 'index'])->name('cart_index');
Route::get('/cart/increment-qty/{id}', [CartController::class, 'incrementQty'])->name('cart_increment_qty');
Route::post('/cart/update-qty/{id}', [CartController::class, 'updateQty'])->name('cart_update_qty');
Route::post('/cart/remove-product/{id}', [CartController::class, 'removeProduct'])->name('cart_remove_product');

Route::get('/checkout', [PaymentController::class, 'index'])->name('checkout');
Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');