<?php
use Junges\Kafka\Facades\Kafka;

class CheckoutProducer
{
    public static function sendCartItemsProducer($cartItems, $userId)
    {
        $producer = Kafka::publishOn('cart_item', env('KAFKA_BROKERS'))
                    ->withBodyKey('cart_items', $cartItems)
                    ->withBodyKey('user_id', $userId);
        $producer->send();
    }
}

?>