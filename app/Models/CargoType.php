<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargoType extends Model
{
    protected $fillable = ['cargo_type'];

    public function setCargoTypeAttribute($value): void
    {
        $this->attributes['cargo_type'] = $value === null
            ? null
            : trim((string) $value);
    }

    public function coldTrips(): HasMany
    {
        return $this->hasMany(ColdTrip::class);
    }
}