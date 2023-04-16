<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Kafka\Producers\CheckoutProducer;
use App\Console\Commands\CartItemActors;

class CartTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_add_to_cart_controller(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);

        $response = $this
                    ->withHeaders(['Accept' => 'application/json', 'content-type' => 'application/json'])
                    ->postJson('/api/add-cart-item', ['user_id' => $user->id, 'cart_item' => [ 'product_id' => $product->id, 'quantity' => 1 ]]);

        $response->assertStatus(200);
    }
    public function test_remove_from_cart_controller(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);  

        $response = $this
                    ->withHeaders(['Accept' => 'application/json', 'content-type' => 'application/json'])
                    ->postJson('/api/delete-cart-item', ['user_id' => $user->id, 'product_id' => $product->id ]);

        $response->assertStatus(200); 
    }
    public function test_add_to_cart_producer(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);

        $this->assertTrue(CheckoutProducer::cartActionProducer([ 'product_id' => $product->id, 'quantity' => 1 ], $user->id, 'add'));
    }
    public function test_remove_from_cart_producer(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);
        
        $this->assertTrue(CheckoutProducer::cartActionProducer($product->id, $user->id, 'delete'));
    }
    /**
     * Testing the consumer handle method is a non ending task
     * therefore I am testing the internal method executed by the consumer
     * If an error will occur on the consumer end, then it will happen when trying to spin up the consumer.
     */
    public function test_add_to_cart_consumer_internal_method(): void
    {
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);
        
        CartItemActors::addToCartHandler(['user_id' => $user->id, 'subject' => ['product_id' => $product->id, 'quantity' => 1]]);
        
        $cart = Cart::where(['user_id' => $user->id, 'status' => 'pending'])->first();

        $this->assertIsNumeric($cart->id);

        $cart_item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1])->first();

        $this->assertIsNumeric($cart_item->id);
    }
    public function test_remove_from_cart_consumer_internal_method(): void
    {  
        $user = User::create(['name' => 'Test User']);
        $product = Product::create(['name' => 'Test Product', 'price' => 1000]);
        $cart = Cart::create(['user_id' => $user->id, 'status' => 'pending']);
        $cart_item = CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        CartItemActors::deleteFromCartHandler(['user_id' => $user->id, 'subject' => $product->id]);

        $cart_item = CartItem::find($cart_item->id);

        $this->assertNull($cart_item);
    }
}
