<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApiController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/api', [ApiController::class, 'handle'])->name('api.handle');