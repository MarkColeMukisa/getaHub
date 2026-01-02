<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'previous_reading',
        'current_reading',
        'units_used',
        'unit_price',
        'total_amount',
        'month',
        'year',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
