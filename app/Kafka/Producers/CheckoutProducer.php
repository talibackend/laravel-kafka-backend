<?php

namespace App\Kafka\Producers;

use Junges\Kafka\Facades\Kafka;

class CheckoutProducer
{
    public static function cartActionProducer($cartItems, $userId, $action)
    {
        $producer = Kafka::publishOn('cart_items', env('KAFKA_BROKERS'))
                    ->withBodyKey('cart_item', $cartItems)
                    ->withBodyKey('user_id', $userId)
                    ->withBodyKey('action', $action);
        $producer->send();
        echo 'Message should have reached consumer.--------------';
    }
}

?>