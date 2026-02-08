<?php

namespace App\Http\Controllers;

use App\Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getCart();
        $total = Cart::calculateTotal();

        return view('cart', [
            'title' => 'Корзина - EasyCart',
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }
}
