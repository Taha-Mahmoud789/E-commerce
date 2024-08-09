<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $response = $this->postJson('/api/user/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user', 'token']);
    }

    // public function test_login()
    // {
    //     $user = User::factory()->create([
    //         'email' => 'test@example.com',
    //         'password' => Hash::make('password'),
    //     ]);

    //     $response = $this->postJson('/api/login', [
    //         'email' => 'test@example.com',
    //         'password' => 'password',
    //     ]);

    //     $response->assertStatus(200)
    //         ->assertJsonStructure(['data' => ['token']]);
    // }

    public function test_admin_register()
    {
        $response = $this->postJson('/api/admin/register', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['admin' => ['token']]]);
    }

    public function test_admin_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['admin' => ['token']]]);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }

    public function test_get_user()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/user', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);
    }
}
