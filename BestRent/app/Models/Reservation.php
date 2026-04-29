<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'pickup_location',
        'dropoff_location',
        'total_price',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
