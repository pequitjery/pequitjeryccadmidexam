<?php

namespace App\Services;

use App\Models\ColdTrip;
use App\Models\Truck;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ColdTripService
{
    private function sanitize(array $data): array
    {
        $data['truck_plate'] = strtoupper(
            trim($data['truck_plate'])
        );

        if (
            isset($data['min_logged_temp'],
                  $data['max_logged_temp'])
            && (float) $data['min_logged_temp'] >
               (float) $data['max_logged_temp']
        ) {
            throw ValidationException::withMessages([
                'min_logged_temp' =>
                    'Minimum temperature cannot exceed maximum temperature.',
            ]);
        }

        $data['temp_compliance_status'] = 'Pending';

        if ($data['max_logged_temp'] !== null) {
            $data['temp_compliance_status'] =
                (float) $data['max_logged_temp'] >
                (float) $data['required_temp_c'] + 2
                    ? 'Temperature Excursion Alert'
                    : 'Compliant';
        }

        return $data;
    }

    private function resolveTruck(string $plate): Truck
    {
        return Truck::firstOrCreate([
            'truck_plate' => strtoupper(trim($plate)),
        ]);
    }

    public function create(array $data): ColdTrip
    {
        return DB::transaction(function () use ($data) {
            $data = $this->sanitize($data);

            $truck = $this->resolveTruck($data['truck_plate']);
            unset($data['truck_plate']);

            $data['truck_id'] = $truck->id;

            return ColdTrip::create($data);
        });
    }

    public function update(int $id, array $data): ColdTrip
    {
        return DB::transaction(function () use ($id, $data) {
            $trip = ColdTrip::findOrFail($id);

            $data = $this->sanitize($data);

            $truck = $this->resolveTruck($data['truck_plate']);
            unset($data['truck_plate']);

            $data['truck_id'] = $truck->id;

            $trip->update($data);

            return $trip->refresh();
        });
    }
}