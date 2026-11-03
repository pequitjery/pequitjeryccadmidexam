<?php

namespace App\Services;

use App\Models\Truck;
use Illuminate\Support\Facades\DB;

class TruckService
{
    public function create(array $data): Truck
    {
        return DB::transaction(function () use ($data) {
            $data['truck_plate'] = strtoupper(trim($data['truck_plate']));

            return Truck::create($data);
        });
    }

    public function update(int $id, array $data): Truck
    {
        return DB::transaction(function () use ($id, $data) {
            $truck = Truck::findOrFail($id);
            $data['truck_plate'] = strtoupper(trim($data['truck_plate']));
            $truck->update($data);

            return $truck->refresh();
        });
    }
}
