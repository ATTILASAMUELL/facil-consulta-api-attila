<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $data = [
            'name' => 'Attila',
            'email' => 'adatila.samuell@gmail.com.brcz',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['id', 'name', 'email', 'created_at', 'updated_at']
                 ])
                 ->assertJson([
                     'message' => 'User registered successfully',
                 ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                         'token',
                         'token_type',
                         'expires_in',
                     ]
                 ])
                 ->assertJson([
                     'message' => 'Login successful',
                 ]);
    }
}
