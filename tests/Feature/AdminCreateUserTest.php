<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_user(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'contact' => '1234567890',
            'room_number' => 'A101',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'contact' => '1234567890',
            'room_number' => 'A101',
        ]);
    }

    public function test_can_create_user_without_email_and_password(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'No Email User',
            'contact' => '555-7777',
            'room_number' => 'B202',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'name' => 'No Email User',
            'contact' => '555-7777',
            'room_number' => 'B202',
        ]);
    }
}
