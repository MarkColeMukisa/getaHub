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
        'vat_amount',
        'paye_amount',
        'rubbish_amount',
        'grand_total',
        'month',
        'year',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
