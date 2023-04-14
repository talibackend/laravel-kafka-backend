<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Kafka\Producers\CheckoutProducer;
// use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $validator = Validator::make($request->post(), [
            'user_id' => 'integer|required|unique:users',
            'cart_items.*.product_id' => 'integer|required|unique:products',
            'cart_items.*.quantiry' => 'integer|required'
        ]);

        $payload = $validator->validate();
        \CheckoutProducer::sendCartItemsProducer($payload['cart_items'], $payload['user_id']);
        return "Hello world....Message should have gotten to consumer and producer.";
    }
}
