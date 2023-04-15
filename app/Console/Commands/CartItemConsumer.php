<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use App\Models\Cart;
use App\Models\CartItem;

class CartItemActors{
    public static function deleteFromCartHandler($payload){
        $user_id = $payload['user_id'];
        $product_id = $payload['subject'];

        $cart = Cart::where(['user_id' => $user_id, 'status' => 'pending'])->first();

        if($cart){
            $search_item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $product_id])->first();
            if($search_item){
                $search_item->delete();
            }
        }
    }
    public static function addToCartHandler($payload){
        $user_id = $payload['user_id'];
        $cart_item = $payload['subject'];

        $cart = Cart::where(['user_id' => $user_id, 'status' => 'pending'])->first();
        if(!$cart){
            $cart = Cart::create([
                'user_id' => $user_id,
                'status' => 'pending'
            ]);
        }
        $search_item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $cart_item['product_id']])->first();
        if($search_item){
            if($search_item->quantity != $cart_item['quantity']){
                $search_item->quantity = $cart_item['quantity'];
                $search_item->save();
            }
        }else{
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $cart_item['product_id'],
                'quantity' => $cart_item['quantity']
            ]);
        }
    }
}

class CartItemConsumer extends Command{
    protected $signature = "consumer:cart_items";

    protected $description = "Consume Kafka messages from 'cart_items'.";

    public static function handle(){
        $consumer = Kafka::createConsumer()
                    ->subscribe('cart_items')
                    ->withBrokers(env('KAFKA_BROKERS'))
                    ->withHandler(function(KafkaConsumerMessage $message) {
                        $body = $message->getBody();
                        $action = $body['action'];
                        switch ($action) {
                            case 'add':
                                unset($body['action']);
                                CartItemActors::addToCartHandler($body);
                                break;
                            case 'delete':
                                unset($body['action']);
                                CartItemActors::deleteFromCartHandler($body);
                                break;
                            default:
                                break;
                        }
                        echo json_encode($body);
                        echo "\n";
                        echo '-----------------';
                        echo "\n";
                    })->build();
        
        $consumer->consume();
    }
}