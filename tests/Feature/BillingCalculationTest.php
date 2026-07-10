<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders tenants and billing config for the client-side calculator', function () {
    $user = User::factory()->create();
    Tenant::factory()->create(['name' => 'Jane Doe', 'room_number' => 'A1']);

    $response = $this->actingAs($user)->get('/calculator');

    $response->assertOk();
    // The bill calculator on this page computes totals client-side from these
    // config values and the tenant list, rather than server-rendered totals.
    $response->assertSee('Jane Doe (Room A1)', false);
    $response->assertSee('data-vat="'.config('billing.vat_rate').'"', false);
    $response->assertSee('data-paye="'.config('billing.paye_amount').'"', false);
    $response->assertSee('data-rubbish="'.config('billing.rubbish_fee').'"', false);
});
