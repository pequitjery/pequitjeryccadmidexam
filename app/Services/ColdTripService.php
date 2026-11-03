<?php

namespace App\Services;

use App\Models\CargoType;
use App\Models\ColdTrip;
use App\Models\Driver;
use App\Models\Hub;
use App\Models\Truck;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ColdTripService
{
    private function normalize(array $data): array
    {
        $min = $data['min_logged_temp'] ?? null;
        $max = $data['max_logged_temp'] ?? null;

        if ($min !== null && $max !== null && (float) $min > (float) $max) {
            throw ValidationException::withMessages([
                'min_logged_temp' => 'Minimum temperature cannot exceed maximum temperature.',
            ]);
        }

        $required = (float) $data['required_temp_c'];
        $data['temp_compliance_status'] = $max === null
            ? 'Pending'
            : ((float) $max > $required + 2 ? 'Temperature Excursion Alert' : 'Compliant');

        return $data;
    }

    public function create(array $data): ColdTrip
    {
        return DB::transaction(function () use ($data) {
            $data = $this->normalize($data);

            $truck = Truck::firstOrCreate([
                'truck_plate' => strtoupper(trim($data['truck_plate'])),
            ]);
            $driver = Driver::firstOrCreate(['driver_name' => trim($data['driver_name'])]);
            $cargo = CargoType::firstOrCreate(['cargo_type' => trim($data['cargo_type'])]);
            $departure = Hub::firstOrCreate(['hub_name' => trim($data['departure_hub'])]);
            $destination = Hub::firstOrCreate(['hub_name' => trim($data['destination_hub'])]);

            return ColdTrip::create([
                'required_temp_c' => $data['required_temp_c'],
                'min_logged_temp' => $data['min_logged_temp'] ?? null,
                'max_logged_temp' => $data['max_logged_temp'] ?? null,
                'dispatch_time' => $data['dispatch_time'],
                'arrival_time' => $data['arrival_time'] ?? null,
                'temp_compliance_status' => $data['temp_compliance_status'],
                'truck_id' => $truck->id,
                'driver_id' => $driver->id,
                'cargo_type_id' => $cargo->id,
                'departure_hub_id' => $departure->id,
                'destination_hub_id' => $destination->id,
            ]);
        });
    }

    public function update(ColdTrip $trip, array $data): ColdTrip
    {
        return DB::transaction(function () use ($trip, $data) {
            $data = $this->normalize($data);

            $truck = Truck::firstOrCreate([
                'truck_plate' => strtoupper(trim($data['truck_plate'])),
            ]);
            $driver = Driver::firstOrCreate(['driver_name' => trim($data['driver_name'])]);
            $cargo = CargoType::firstOrCreate(['cargo_type' => trim($data['cargo_type'])]);
            $departure = Hub::firstOrCreate(['hub_name' => trim($data['departure_hub'])]);
            $destination = Hub::firstOrCreate(['hub_name' => trim($data['destination_hub'])]);

            $trip->update([
                'required_temp_c' => $data['required_temp_c'],
                'min_logged_temp' => $data['min_logged_temp'] ?? null,
                'max_logged_temp' => $data['max_logged_temp'] ?? null,
                'dispatch_time' => $data['dispatch_time'],
                'arrival_time' => $data['arrival_time'] ?? null,
                'temp_compliance_status' => $data['temp_compliance_status'],
                'truck_id' => $truck->id,
                'driver_id' => $driver->id,
                'cargo_type_id' => $cargo->id,
                'departure_hub_id' => $departure->id,
                'destination_hub_id' => $destination->id,
            ]);

            return $trip->refresh();
        });
    }
}
