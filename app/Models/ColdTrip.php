<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColdTrip extends Model
{
    protected $fillable = [
        'truck_id',
        'driver_id',
        'cargo_type_id',
        'departure_hub_id',
        'destination_hub_id',
        'required_temp_c',
        'min_logged_temp',
        'max_logged_temp',
        'dispatch_time',
        'arrival_time',
        'temp_compliance_status',
    ];

    protected function casts(): array
    {
        return [
            'required_temp_c' => 'decimal:2',
            'min_logged_temp' => 'decimal:2',
            'max_logged_temp' => 'decimal:2',
            'dispatch_time' => 'datetime',
            'arrival_time' => 'datetime',
        ];
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function cargoType(): BelongsTo
    {
        return $this->belongsTo(CargoType::class);
    }

    public function departureHub(): BelongsTo
    {
        return $this->belongsTo(
            Hub::class,
            'departure_hub_id'
        );
    }

    public function destinationHub(): BelongsTo
    {
        return $this->belongsTo(
            Hub::class,
            'destination_hub_id'
        );
    }
}