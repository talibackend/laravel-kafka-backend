<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kafka\Producers\CheckoutProducer;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $payload = $request->validate([
            'user_id' => 'integer|required',
            'cart_items.*.product_id' => 'integer|required',
            'cart_items.*.quantity' => 'integer|required'
        ]);

        //Am trying to do some manual validation here.

        $user_id = $payload['user_id'];
        $user = DB::table('users')->where('id', $user_id)->first();

        if(!$user){
            return response()->json(['ok' => false, 'message' => 'Invalid user id provided.'])->setStatusCode(400);
        }

        for ($i=0; $i < count($payload['cart_items']); $i++) { 
            $currentItemId = $payload['cart_items'][$i]['product_id'];
            $product = DB::table('products')->where('id', $currentItemId)->first();

            if(!$product){
                return response()->json(['ok' => false, 'message' => 'Product with the id of '.$currentItemId.' not found.'])->setStatusCode(400);
            }
        }

        CheckoutProducer::cartActionProducer($payload['cart_items'], $payload['user_id'], 'add');
        return "Hello world....Message should have gotten to consumer and producer.";
    }
}
