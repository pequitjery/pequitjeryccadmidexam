<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColdTripRequest;
use App\Http\Requests\UpdateColdTripRequest;
use App\Models\ColdTrip;
use App\Services\ColdTripService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ColdTripController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('cold-trips/index', [
            'trips' => ColdTrip::query()
                ->with(['truck', 'driver', 'cargoType', 'departureHub', 'destinationHub'])
                ->latest('dispatch_time')
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('cold-trips/create');
    }

    public function store(StoreColdTripRequest $request, ColdTripService $service): RedirectResponse
    {
        $service->create($request->validated());

        return to_route('cold-trips.index')->with('success', 'Cold trip created successfully.');
    }

    public function show(ColdTrip $coldTrip): Response
    {
        return Inertia::render('cold-trips/show', [
            'trip' => $coldTrip->load(['truck', 'driver', 'cargoType', 'departureHub', 'destinationHub']),
        ]);
    }

    public function edit(ColdTrip $coldTrip): Response
    {
        return Inertia::render('cold-trips/edit', [
            'trip' => $coldTrip->load(['truck', 'driver', 'cargoType', 'departureHub', 'destinationHub']),
        ]);
    }

    public function update(UpdateColdTripRequest $request, ColdTrip $coldTrip, ColdTripService $service): RedirectResponse
    {
        $service->update($coldTrip, $request->validated());

        return to_route('cold-trips.index')->with('success', 'Cold trip updated successfully.');
    }

    public function destroy(ColdTrip $coldTrip): RedirectResponse
    {
        $coldTrip->delete();

        return to_route('cold-trips.index')->with('success', 'Cold trip deleted successfully.');
    }
}
