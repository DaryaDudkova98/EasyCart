<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;

class UpdateCartController extends Controller
{
    public function update(Request $request, $id)
    {

        $quantity = $request->input('quantity', 1);
        
        $result = Cart::update($id, $quantity);
        
        return redirect()->route('cart.index')->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}
