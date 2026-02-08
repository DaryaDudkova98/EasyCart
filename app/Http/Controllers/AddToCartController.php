<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;

class AddToCartController extends Controller
{
    public function add(Request $request, $id)
    {

        $productData = session('products_data', []);
        
        // Проверяем, существует ли товар
        if (!isset($productData[$id])) {
            return back()->with('error', 'Товар не найден!');
        }
        
        $result = Cart::add($id, $productData[$id]);
        
        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}
