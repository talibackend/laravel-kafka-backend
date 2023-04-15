<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Illuminate\Support\Facades\DB;

class CartItemActors{
    public static function addToCartHandler($payload){
        $user_id = $payload['user_id'];
        $cart_items = $payload['cart_items'];

        $cart = DB::table('carts')->where(['user_id' => $user_id, 'status' => 'pending'])->first();
        if(!$cart){
            $cart = DB::table('carts')->insert([
                'user_id' => $user_id,
                'status' => 'pending',
                'createdAt' => new \Date(),
                'updatedAt' => new \Date()
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