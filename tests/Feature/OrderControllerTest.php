<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    protected function headers()
    {
        return ['Authorization' => "Bearer {$this->token}"];
    }

    public function test_index_returns_orders()
    {
        Order::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/orders', $this->headers());

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'status', 'total', 'created_at', 'updated_at', 'items']
                     ]
                 ]);
    }

    public function test_store_creates_order()
    {
        $product = Product::factory()->create(['price' => 50]);

        $payload = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload, $this->headers());

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'status',
                         'total',
                         'items' => [
                             '*' => ['id', 'quantity', 'price', 'subtotal']
                         ]
                     ]
                 ]);

        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id, 'total' => 100]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_show_returns_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/orders/{$order->id}", $this->headers());

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['id', 'status', 'total', 'created_at', 'updated_at', 'items']
                 ]);
    }

    public function test_update_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create(['price' => 25]);

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 4]
            ]
            // remove 'status' because your UpdateOrderRequest doesn't accept it
        ];

        $response = $this->putJson("/api/orders/{$order->id}", $payload, $this->headers());

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['id', 'status', 'total', 'created_at', 'updated_at', 'items']
                 ]);

        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 4]);
    }

    public function test_destroy_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/orders/{$order->id}", [], $this->headers());

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
