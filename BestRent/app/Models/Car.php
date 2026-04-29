<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Car extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'category',
        'year',
        'plate_number',
        'color',
        'fuel_type',
        'transmission',
        'seats',
        'daily_price',
        'status',
        'image',
        'image_type',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'daily_price' => 'decimal:2',
        ];
    }

    public static function transmissionOptions(): array
    {
        return ['Automata', 'Manuális'];
    }

    public static function fuelTypeOptions(): array
    {
        return ['Benzin', 'Dízel', 'Hibrid', 'Elektromos'];
    }

    public static function categoryOptions(): array
    {
        return ['Sedan', 'Hatchback', 'SUV', 'Terepjáró', 'Pickup', 'Cabrio', 'Coupe', 'Kombi'];
    }

    public static function statusOptions(): array
    {
        return ['available', 'maintenance', 'unavailable'];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if ($this->image_type === 'link' || Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Str::startsWith($this->image, ['/storage/', 'storage/'])) {
            return asset(ltrim($this->image, '/'));
        }

        return asset('storage/' . $this->image);
    }

    public function getFuelTypeAttribute(?string $value): ?string
    {
        return self::normalizeHungarianText($value);
    }

    public function getTransmissionAttribute(?string $value): ?string
    {
        return self::normalizeHungarianText($value);
    }

    public function getCategoryAttribute(?string $value): ?string
    {
        return self::normalizeHungarianText($value);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Elérhető',
            'maintenance' => 'Karbantartás',
            'unavailable' => 'Nem elérhető',
            default => $this->status,
        };
    }

    private static function normalizeHungarianText(?string $value): ?string
    {
        if (! $value) {
            return $value;
        }

        return match ($value) {
            'Manu�lis' => 'Manuális',
            'D�zel' => 'Dízel',
            'Terepj�r�' => 'Terepjáró',
            default => $value,
        };
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
