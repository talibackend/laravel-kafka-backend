<?php

namespace App\Kafka\Producers;

use Junges\Kafka\Facades\Kafka;

class CheckoutProducer
{
    public static function cartActionProducer($subject, $userId, $action)
    {
        $producer = Kafka::publishOn('cart_items', env('KAFKA_BROKERS'))
                    ->withBodyKey('subject', $subject)
                    ->withBodyKey('user_id', $userId)
                    ->withBodyKey('action', $action);
        $producer->send();
    }
}

?>