<?php

namespace App\Http\Controllers;

use App\Models\CargoType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CargoTypeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('catalog/index', [
            'title' => 'Cargo Types',
            'singular' => 'Cargo type',
            'basePath' => '/cargo-types',
            'items' => CargoType::query()
                ->withCount('coldTrips')
                ->orderBy('cargo_type')
                ->paginate(10)
                ->withQueryString()
                ->through(fn (CargoType $cargoType) => [
                    'id' => $cargoType->id,
                    'name' => $cargoType->cargo_type,
                    'cold_trips_count' => $cargoType->cold_trips_count,
                ]),
        ]);
    }

    public function create(): Response
    {
        return $this->form('Add Cargo Type', '/cargo-types', 'post', '');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['cargo_type' => trim((string) $request->input('cargo_type'))]);
        $data = $request->validate([
            'cargo_type' => ['required', 'string', 'max:255', 'unique:cargo_types,cargo_type'],
        ]);

        CargoType::create($data);

        return to_route('cargo-types.index')->with('success', 'Cargo type added successfully.');
    }

    public function edit(CargoType $cargoType): Response
    {
        return $this->form('Edit Cargo Type', "/cargo-types/{$cargoType->id}", 'put', $cargoType->cargo_type);
    }

    public function update(Request $request, CargoType $cargoType): RedirectResponse
    {
        $request->merge(['cargo_type' => trim((string) $request->input('cargo_type'))]);
        $data = $request->validate([
            'cargo_type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cargo_types', 'cargo_type')->ignore($cargoType->id),
            ],
        ]);

        $cargoType->update($data);

        return to_route('cargo-types.index')->with('success', 'Cargo type updated successfully.');
    }

    public function destroy(CargoType $cargoType): RedirectResponse
    {
        if ($cargoType->coldTrips()->exists()) {
            return to_route('cargo-types.index')->with('error', 'This cargo type is used by cold trips and cannot be deleted.');
        }

        $cargoType->delete();

        return to_route('cargo-types.index')->with('success', 'Cargo type deleted successfully.');
    }

    private function form(string $title, string $action, string $method, string $value): Response
    {
        return Inertia::render('catalog/form', [
            'title' => $title,
            'collectionTitle' => 'Cargo Types',
            'singular' => 'Cargo type',
            'basePath' => '/cargo-types',
            'fieldName' => 'cargo_type',
            'fieldLabel' => 'Cargo type',
            'action' => $action,
            'method' => $method,
            'value' => $value,
        ]);
    }
}
