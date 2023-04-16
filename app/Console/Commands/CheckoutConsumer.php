<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CheckoutConsumer extends Command
{
    protected $signature = "consumer:checkout";

    protected $description = "Consume Kafka messages from 'checkout'.";

    public function handle()
    {
        $consumer = Kafka::createConsumer()
                    ->subscribe('checkout')
                    ->withBrokers(env('KAFKA_BROKERS'))
                    ->withHandler(function(KafkaConsumerMessage $message) {
                        $body = $message->getBody();
                        $user_id = $body['user_id'];
                        $cart = Cart::where(['user_id' => $user_id, 'status' => 'pending'])->first();
                        if($cart){
                            $cart_items = CartItem::where(['cart_id' => $cart->id])->get();
                            $total = 0;
                            for ($i=0; $i < count($cart_items); $i++) {
                                $product = Product::find($cart_items[$i]->product_id);
                                if(!$product){
                                    return;
                                }else{
                                    $total += $product->price * $cart_items[$i]->quantity;
                                }
                            }
                            Order::create([
                                'user_id' => $user_id,
                                'cart_id' => $cart->id,
                                'total' => $total
                            ]);
                            $cart->status = 'completed';
                            $cart->save();
                        }
                        echo json_encode($body);
                        echo "\n";
                        echo '-----------------';
                        echo "\n";
                    })->build();
        
        $consumer->consume();
    }
}
