<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/order/payment', [OrderController::class, 'payment'])->name('order.payment');
    Route::get('/order/history', [OrderController::class, 'index'])->name('order.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
});

require __DIR__.'/auth.php';
