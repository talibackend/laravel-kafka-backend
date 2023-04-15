<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kafka\Producers\CheckoutProducer;
use App\Models\Product;
use App\Models\User;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $payload = $request->validate([
            'user_id' => 'integer|required',
            'cart_item.product_id' => 'integer|required',
            'cart_item.quantity' => 'integer|required'
        ]);

        //Am trying to do some manual validation here.

        $user_id = $payload['user_id'];
        $user = User::find($user_id);

        if(!$user){
            return response()->json(['ok' => false, 'message' => 'Invalid user id provided.'])->setStatusCode(400);
        }
        $product = Product::find($payload['cart_item']['product_id']);

        if(!$product){
            return response()->json(['ok' => false, 'message' => 'Product not found.'])->setStatusCode(400);
        }

        CheckoutProducer::cartActionProducer($payload['cart_item'], $payload['user_id'], 'add');
        return "Hello world....Message should have gotten to consumer and producer.";
    }
}
