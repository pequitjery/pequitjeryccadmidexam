<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hub extends Model
{
    protected $fillable = ['hub_name'];

    public function setHubNameAttribute($value): void
    {
        $this->attributes['hub_name'] = $value === null
            ? null
            : trim((string) $value);
    }

    public function departureTrips(): HasMany
    {
        return $this->hasMany(ColdTrip::class, 'departure_hub_id');
    }

    public function destinationTrips(): HasMany
    {
        return $this->hasMany(ColdTrip::class, 'destination_hub_id');
    }
}