<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Api\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', function () {
    return view('pages.cart');
})->name('cart');

// Rutas de checkout (requieren autenticación)
Route::middleware('auth.user')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');

// Rutas de pedidos del usuario (requieren autenticación)
Route::middleware('auth.user')->group(function () {
    Route::get('/my-orders', [App\Http\Controllers\Web\UserOrderController::class, 'index'])->name('my-orders');
    Route::get('/orders/{order}', [App\Http\Controllers\Web\UserOrderController::class, 'show'])->name('orders.show');
});

// Rutas de autenticación
Route::get('/login', [App\Http\Controllers\Web\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\Web\AuthController::class, 'login'])->name('login.post');
Route::get('/register', [App\Http\Controllers\Web\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\Web\AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [App\Http\Controllers\Web\AuthController::class, 'logout'])->name('logout');

// Rutas de administración
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de productos
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);

    // Rutas de categorías
    Route::get('/categories', [CategoryController::class, 'adminIndex'])->name('categories.index');
    Route::resource('categories', CategoryController::class)->except(['index']);

    // Rutas de pedidos
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::resource('orders', OrderController::class)->except(['index']);

    // Rutas de usuarios
    Route::get('/users', [UserController::class, 'adminIndex'])->name('users.index');
    Route::resource('users', UserController::class)->except(['index']);

    // Rutas de configuración
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/maintenance', [SettingsController::class, 'toggleMaintenance'])->name('settings.maintenance');
});

Route::get('/orders/{order}/pay', [App\Http\Controllers\Web\OrderController::class, 'pay'])->name('orders.pay');
Route::post('/orders/{order}/pay/confirm', [App\Http\Controllers\Web\OrderController::class, 'confirmPayment'])->name('orders.pay.confirm');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/items', [CartController::class, 'items'])->name('cart.items');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


