<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    // Attributes are mass-assignable via $fillable; rely on Eloquent's built-in create().
    protected $fillable = [
        'name', 'contact', 'room_number',
    ];

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function latestBill(): HasOne
    {
        return $this->hasOne(Bill::class)->latestOfMany();
    }
}
