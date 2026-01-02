<?php

use App\Models\User;
use App\Models\Tenant;
use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a bill using last bill current reading as previous', function(){
    $user = User::factory()->create(['is_admin' => true]);
    $tenant = Tenant::factory()->create();
    $first = Bill::factory()->create(['tenant_id' => $tenant->id, 'previous_reading' => 0, 'current_reading' => 100, 'units_used' => 100]);

    $response = $this->actingAs($user)->post(route('bills.store'), [
        'tenant_id' => $tenant->id,
        'current_reading' => 160,
        'month' => 'August',
        'unit_price' => 3516,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('bills', [
        'tenant_id' => $tenant->id,
        'previous_reading' => 100,
        'current_reading' => 160,
        'units_used' => 60,
    ]);
});

it('rejects bill when current less than previous', function(){
    $user = User::factory()->create(['is_admin' => true]);
    $tenant = Tenant::factory()->create();
    Bill::factory()->create(['tenant_id' => $tenant->id, 'previous_reading' => 0, 'current_reading' => 200, 'units_used' => 200]);

    $response = $this->actingAs($user)->post(route('bills.store'), [
        'tenant_id' => $tenant->id,
        'current_reading' => 150,
        'month' => 'August',
        'unit_price' => 3516,
    ]);

    $response->assertSessionHasErrors('current_reading');
});
