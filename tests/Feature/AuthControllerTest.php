<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_user_successfully(): void
    {
        $payload = [
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);


        $response->assertStatus(200)
                ->assertJsonPath('data.success', true)
                ->assertJsonStructure([
                    'data' => [
                        'success',
                        'token',
                        'user' => ['id', 'name', 'email', 'created_at'],
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'user1@example.com',
        ]);
    }

    /** @test */
    public function it_logs_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.success', true)
                ->assertJsonStructure([
                    'data' => [
                        'success',
                        'token',
                        'user' => ['id', 'name', 'email', 'created_at'],
                    ]
                ]);
    }

    /** @test */
    public function it_fails_to_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Invalid credentials',
                 ]);
    }

    /** @test */
    public function test_returns_authenticated_user_data(): void
    {
        // Create a user manually
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Generate JWT token using JWTAuth
        $token = JWTAuth::fromUser($user);

        // Include token in Authorization header
        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->getJson('/api/me');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('user.id', $user->id)
                 ->assertJsonPath('user.name', $user->name)
                 ->assertJsonPath('user.email', $user->email);
    }
}
