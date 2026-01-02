<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        $previous = $this->faker->numberBetween(0, 5000);
        $units = $this->faker->numberBetween(0, 500);
        $current = $previous + $units;
        $unitPrice = 3516;
        return [
            'tenant_id' => Tenant::factory(),
            'previous_reading' => $previous,
            'current_reading' => $current,
            'units_used' => $units,
            'unit_price' => $unitPrice,
            'total_amount' => $units * $unitPrice,
            'month' => now()->format('F'),
            'year' => now()->year,
        ];
    }
}
