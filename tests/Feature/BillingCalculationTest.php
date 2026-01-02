<?php

use App\Models\User;
use App\Models\Tenant;
use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders correct calculated totals on index page', function(){
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create(['room_number' => 'A1']);

    // base: units_used * unit_price
    $previous = 50; $current = 130; $units = $current - $previous; $unitPrice = 3516; $base = $units * $unitPrice;

    Bill::factory()->create([
        'tenant_id' => $tenant->id,
        'previous_reading' => $previous,
        'current_reading' => $current,
        'units_used' => $units,
        'unit_price' => $unitPrice,
        'total_amount' => $base,
        'month' => 'August',
        'year' => now()->year,
    ]);

    $vatRate = config('billing.vat_rate');
    $paye = config('billing.paye_amount');
    $rubbish = config('billing.rubbish_fee');
    $expectedVat = (int) round($base * $vatRate);
    $expectedGrand = $base + $expectedVat + $paye + $rubbish;

    $response = $this->actingAs($user)->get('/index');

    $response->assertOk();
    // Assert base and rubbish and final grand total appear formatted
    $response->assertSee(number_format($base), false);
    $response->assertSee(number_format($rubbish), false);
    $response->assertSee(number_format($expectedGrand), false);
});
