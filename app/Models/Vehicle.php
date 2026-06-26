<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'Available';
    public const STATUS_IN_USE = 'In-Use';
    public const STATUS_MAINTENANCE = 'Maintenance';

    public const STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_IN_USE,
        self::STATUS_MAINTENANCE,
    ];

    protected $fillable = [
        'vehicle_code',
        'plate_number',
        'brand',
        'model',
        'vehicle_type',
        'capacity',
        'fuel_type',
        'status',
        'last_service_date',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'last_service_date' => 'date:Y-m-d',
    ];

    public function dispatches(): HasMany
    {
        return $this->hasMany(VehicleDispatch::class);
    }
}
