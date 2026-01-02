<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_tenant(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $tenant = Tenant::factory()->create();

        $response = $this->actingAs($user)->patch(route('tenants.update', $tenant), [
            'name' => 'Updated Name',
            'contact' => '999-0000',
            'room_number' => 'Z909',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'name' => 'Updated Name',
            'contact' => '999-0000',
            'room_number' => 'Z909',
        ]);
    }

    public function test_admin_can_delete_tenant(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $tenant = Tenant::factory()->create();

        $response = $this->actingAs($user)->delete(route('tenants.destroy', $tenant));
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('tenants', [ 'id' => $tenant->id ]);
    }

    public function test_non_admin_cannot_update_tenant(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tenant = Tenant::factory()->create();

        $response = $this->actingAs($user)->patch(route('tenants.update', $tenant), [
            'name' => 'Should Not Work',
            'contact' => '999-0000',
            'room_number' => 'Z909',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('tenants', ['name' => 'Should Not Work']);
    }

    public function test_non_admin_cannot_delete_tenant(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tenant = Tenant::factory()->create();

        $response = $this->actingAs($user)->delete(route('tenants.destroy', $tenant));
        $response->assertForbidden();
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id]);
    }
}
