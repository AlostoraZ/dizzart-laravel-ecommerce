<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [ShopController::class, 'show'])->name('shop.show');

/*
|--------------------------------------------------------------------------
| Cart Routes (guests can view/add, checkout requires auth)
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo.apply');
Route::delete('/cart/promo/remove', [CartController::class, 'removePromo'])->name('cart.promo.remove');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.mine');
});

Route::get('/orders/{order}', [OrderController::class, 'track'])->name('orders.track');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

    Route::post('/promo-codes', [AdminController::class, 'storePromoCode'])->name('promo-codes.store');
    Route::put('/promo-codes/{promo_code}', [AdminController::class, 'updatePromoCode'])->name('promo-codes.update');
    Route::delete('/promo-codes/{promo_code}', [AdminController::class, 'destroyPromoCode'])->name('promo-codes.destroy');

    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
});

require __DIR__ . '/auth.php';
