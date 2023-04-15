<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

class CartItemConsumer extends Command{
    protected $signature = "consumer:cart_items";

    protected $description = "Consume Kafka messages from 'cart_items'.";

    public static function handle(){
        $consumer = Kafka::createConsumer()
                    ->subscribe('cart_items')
                    ->withBrokers(env('KAFKA_BROKERS'))
                    ->withHandler(function(KafkaConsumerMessage $message) {
                        $body = $message->getBody();
                        echo json_encode($body);
                    })->build();
        
        $consumer->consume();
    }
}