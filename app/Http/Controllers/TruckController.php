<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use App\Models\Truck;
use App\Services\TruckService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TruckController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('trucks/index', [
            'trucks' => Truck::query()
                ->withCount('coldTrips')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('trucks/create');
    }

    public function store(StoreTruckRequest $request, TruckService $service): RedirectResponse
    {
        $service->create($request->validated());

        return to_route('trucks.index')->with('success', 'Truck registered successfully.');
    }

    public function show(Truck $truck): Response
    {
        return Inertia::render('trucks/show', [
            'truck' => $truck->load([
                'coldTrips' => fn ($query) => $query
                    ->with(['driver', 'cargoType', 'departureHub', 'destinationHub'])
                    ->latest('dispatch_time'),
            ]),
        ]);
    }

    public function edit(Truck $truck): Response
    {
        return Inertia::render('trucks/edit', [
            'truck' => $truck,
        ]);
    }

    public function update(UpdateTruckRequest $request, Truck $truck, TruckService $service): RedirectResponse
    {
        $service->update($truck->id, $request->validated());

        return to_route('trucks.index')->with('success', 'Truck updated successfully.');
    }

    public function destroy(Truck $truck): RedirectResponse
    {
        if ($truck->coldTrips()->exists()) {
            return to_route('trucks.index')
                ->with('error', 'This truck has cold trips and cannot be deleted.');
        }

        $truck->delete();

        return to_route('trucks.index')->with('success', 'Truck deleted successfully.');
    }
}
