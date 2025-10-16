<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function index_returns_payments()
    {
        $response = $this->getJson('/api/payments');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'order_id', 'method', 'status', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    /** @test */
    public function store_processes_payment_successfully()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'confirmed',
        ]);

        $payload = ['method' => 'credit_card'];

        $response = $this->postJson("/api/orders/{$order->id}/pay", $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => ['id', 'order_id', 'method', 'status', 'created_at'],
                     'message',
                 ]);
    }

    /** @test */
    public function store_returns_error_for_invalid_method()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'confirmed',
        ]);

        $payload = ['method' => 'bitcoin'];

        $response = $this->postJson("/api/orders/{$order->id}/pay", $payload);

        $response->assertStatus(422);
    }

    /** @test */
    public function store_returns_error_for_unconfirmed_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        $payload = ['method' => 'paypal'];

        $response = $this->postJson("/api/orders/{$order->id}/pay", $payload);

        $response->assertStatus(400)
                 ->assertJsonStructure(['error']);
    }
}
