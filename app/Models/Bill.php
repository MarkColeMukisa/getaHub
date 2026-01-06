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
        'notified_at',
        'notification_error',
        'notification_attempts',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'notified_at' => 'datetime',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
