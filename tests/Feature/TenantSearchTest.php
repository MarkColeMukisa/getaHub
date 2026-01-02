<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_search_filters_tenants(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        Tenant::factory()->create(['name' => 'Alice Johnson', 'contact' => '111', 'room_number' => 'A1']);
        Tenant::factory()->create(['name' => 'Bob Smith', 'contact' => '222', 'room_number' => 'B2']);
        Tenant::factory()->create(['name' => 'Charlie', 'contact' => '333', 'room_number' => 'C3']);

    $response = $this->actingAs($user)->get(route('dashboard', ['search' => 'Bob']));
    $response->assertStatus(200);
    $response->assertSee('Bob Smith');
    $response->assertDontSee('Alice Johnson');
    $response->assertDontSee('Charlie');
    }

    public function test_non_admin_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertForbidden();
    }
}
