<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_export_filtered_sorted_csv(): void
    {
        $user = User::factory()->create();
        Tenant::factory()->create(['name' => 'Alpha', 'contact' => '111', 'room_number' => 'R1']);
        Tenant::factory()->create(['name' => 'Beta', 'contact' => '222', 'room_number' => 'R2']);
        Tenant::factory()->create(['name' => 'Gamma', 'contact' => '333', 'room_number' => 'R3']);

        $response = $this->actingAs($user)->get(route('tenants.export', [
            'search' => 'a', // matches Alpha & Gamma & Beta
            'sort' => 'name',
            'direction' => 'asc',
        ]));

        $response->assertOk();
    $this->assertStringStartsWith('text/csv', $response->headers->get('Content-Type'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Alpha', $content);
        $this->assertStringContainsString('Gamma', $content);
    }
}
