<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = ['driver_name'];

    public function setDriverNameAttribute($value): void
    {
        $this->attributes['driver_name'] = $value === null
            ? null
            : trim((string) $value);
    }

    public function coldTrips(): HasMany
    {
        return $this->hasMany(ColdTrip::class);
    }
}