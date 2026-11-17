<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DriverController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('catalog/index', [
            'title' => 'Drivers',
            'singular' => 'Driver',
            'basePath' => '/drivers',
            'items' => Driver::query()
                ->withCount('coldTrips')
                ->orderBy('driver_name')
                ->paginate(10)
                ->withQueryString()
                ->through(fn (Driver $driver) => [
                    'id' => $driver->id,
                    'name' => $driver->driver_name,
                    'cold_trips_count' => $driver->cold_trips_count,
                ]),
        ]);
    }

    public function create(): Response
    {
        return $this->form('Add Driver', '/drivers', 'post', '');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['driver_name' => trim((string) $request->input('driver_name'))]);
        $data = $request->validate([
            'driver_name' => ['required', 'string', 'max:255', 'unique:drivers,driver_name'],
        ]);

        Driver::create($data);

        return to_route('drivers.index')->with('success', 'Driver added successfully.');
    }

    public function edit(Driver $driver): Response
    {
        return $this->form('Edit Driver', "/drivers/{$driver->id}", 'put', $driver->driver_name);
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $request->merge(['driver_name' => trim((string) $request->input('driver_name'))]);
        $data = $request->validate([
            'driver_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'driver_name')->ignore($driver->id),
            ],
        ]);

        $driver->update($data);

        return to_route('drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        if ($driver->coldTrips()->exists()) {
            return to_route('drivers.index')->with('error', 'This driver has cold trips and cannot be deleted.');
        }

        $driver->delete();

        return to_route('drivers.index')->with('success', 'Driver deleted successfully.');
    }

    private function form(string $title, string $action, string $method, string $value): Response
    {
        return Inertia::render('catalog/form', [
            'title' => $title,
            'collectionTitle' => 'Drivers',
            'singular' => 'Driver',
            'basePath' => '/drivers',
            'fieldName' => 'driver_name',
            'fieldLabel' => 'Driver name',
            'action' => $action,
            'method' => $method,
            'value' => $value,
        ]);
    }
}
