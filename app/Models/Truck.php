<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Truck extends Model
{
    protected $fillable = ['truck_plate'];

    public function setTruckPlateAttribute($value): void
    {
        $this->attributes['truck_plate'] = $value === null
            ? null
            : strtoupper(trim((string) $value));
    }

    public function coldTrips(): HasMany
    {
        return $this->hasMany(ColdTrip::class);
    }
}
