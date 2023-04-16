<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Kafka\Producers\CheckoutProducer;
use App\Console\Commands\CheckoutActors;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_checkout_controller(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);
        $cart = Cart::create(['user_id' => $user->id, 'status' => 'pending']);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this
                    ->withHeaders(['Accept' => 'application/json', 'content-type' => 'application/json'])
                    ->postJson('/api/checkout', ['user_id' => $user->id]);


        $response->assertStatus(200);
    }
    public function test_checkout_producer(): void
    {
        $user = User::create(['name' => 'Test User']);

        $this->assertTrue(CheckoutProducer::checkout($user->id));
    }
    /**
     * Testing the consumer handle method is a non ending task
     * therefore I am testing the internal method executed by the consumer
     * If an error will occur on the consumer end, then it will happen when trying to spin up the consumer.
     */
    public function test_checkout_internal_method(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);
        $cart = Cart::create(['user_id' => $user->id, 'status' => 'pending']);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);
   
        CheckoutActors::checkoutHandler($user->id);

        $order = Order::where(['cart_id' => $cart->id, 'user_id' => $user->id, 'total' => 1000])->first();

        $this->assertIsNumeric($order->id);

        $cart = Cart::find($cart->id);

        $this->assertEquals($cart->status, 'completed');
    }
}
