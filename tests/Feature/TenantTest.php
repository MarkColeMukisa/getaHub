<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_tenant(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)->post(route('tenants.store'), [
            'name' => 'Tenant One',
            'contact' => '555-9999',
            'room_number' => 'C303',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tenants', [
            'name' => 'Tenant One',
            'contact' => '555-9999',
            'room_number' => 'C303',
        ]);
    }

    public function test_non_admin_cannot_create_tenant(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->post(route('tenants.store'), [
            'name' => 'Blocked Tenant',
            'contact' => '000-0000',
            'room_number' => 'X000',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('tenants', [ 'name' => 'Blocked Tenant' ]);
    }
}
