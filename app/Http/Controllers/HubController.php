<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class HubController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('catalog/index', [
            'title' => 'Hubs',
            'singular' => 'Hub',
            'basePath' => '/hubs',
            'items' => Hub::query()
                ->withCount(['departureTrips', 'destinationTrips'])
                ->orderBy('hub_name')
                ->paginate(10)
                ->withQueryString()
                ->through(fn (Hub $hub) => [
                    'id' => $hub->id,
                    'name' => $hub->hub_name,
                    'cold_trips_count' => $hub->departure_trips_count + $hub->destination_trips_count,
                ]),
        ]);
    }

    public function create(): Response
    {
        return $this->form('Add Hub', '/hubs', 'post', '');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['hub_name' => trim((string) $request->input('hub_name'))]);
        $data = $request->validate([
            'hub_name' => ['required', 'string', 'max:255', 'unique:hubs,hub_name'],
        ]);

        Hub::create($data);

        return to_route('hubs.index')->with('success', 'Hub added successfully.');
    }

    public function edit(Hub $hub): Response
    {
        return $this->form('Edit Hub', "/hubs/{$hub->id}", 'put', $hub->hub_name);
    }

    public function update(Request $request, Hub $hub): RedirectResponse
    {
        $request->merge(['hub_name' => trim((string) $request->input('hub_name'))]);
        $data = $request->validate([
            'hub_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hubs', 'hub_name')->ignore($hub->id),
            ],
        ]);

        $hub->update($data);

        return to_route('hubs.index')->with('success', 'Hub updated successfully.');
    }

    public function destroy(Hub $hub): RedirectResponse
    {
        if ($hub->departureTrips()->exists() || $hub->destinationTrips()->exists()) {
            return to_route('hubs.index')->with('error', 'This hub is used by cold trips and cannot be deleted.');
        }

        $hub->delete();

        return to_route('hubs.index')->with('success', 'Hub deleted successfully.');
    }

    private function form(string $title, string $action, string $method, string $value): Response
    {
        return Inertia::render('catalog/form', [
            'title' => $title,
            'collectionTitle' => 'Hubs',
            'singular' => 'Hub',
            'basePath' => '/hubs',
            'fieldName' => 'hub_name',
            'fieldLabel' => 'Hub name',
            'action' => $action,
            'method' => $method,
            'value' => $value,
        ]);
    }
}
