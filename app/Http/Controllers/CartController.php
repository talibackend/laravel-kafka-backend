<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $payload = $request->validate([
            'user_id' => 'string|'
        ]);
    }
}
