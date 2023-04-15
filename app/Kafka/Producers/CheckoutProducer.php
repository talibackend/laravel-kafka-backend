<?php

namespace App\Kafka\Producers;

use Junges\Kafka\Facades\Kafka;

class CheckoutProducer
{
    public static function sendCartItemsProducer($cartItems, $userId)
    {
        $producer = Kafka::publishOn('cart_items', env('KAFKA_BROKERS'))
                    ->withBodyKey('cart_items', $cartItems)
                    ->withBodyKey('user_id', $userId);
        $producer->send();
        echo 'Message should have reached consumer.--------------';
    }
}

?>