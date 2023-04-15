<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kafka\Producers\CheckoutProducer;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $payload = $request->validate([
            'user_id' => 'integer|required',
            'cart_items.*.product_id' => 'integer|required',
            'cart_items.*.quantity' => 'integer|required'
        ]);

        CheckoutProducer::sendCartItemsProducer($payload['cart_items'], $payload['user_id']);
        return "Hello world....Message should have gotten to consumer and producer.";
    }
}
