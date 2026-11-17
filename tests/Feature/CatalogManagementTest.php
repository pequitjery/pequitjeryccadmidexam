<?php

namespace Tests\Feature;

use App\Models\CargoType;
use App\Models\ColdTrip;
use App\Models\Driver;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CatalogManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_manage_drivers_cargo_types_and_hubs(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('drivers.store'), ['driver_name' => '  Alex Driver  '])
            ->assertRedirect(route('drivers.index'));
        $driver = Driver::firstOrFail();
        $this->assertSame('Alex Driver', $driver->driver_name);

        $this->post(route('cargo-types.store'), ['cargo_type' => ' Fresh produce '])
            ->assertRedirect(route('cargo-types.index'));
        $cargoType = CargoType::firstOrFail();
        $this->assertSame('Fresh produce', $cargoType->cargo_type);

        $this->post(route('hubs.store'), ['hub_name' => ' North Hub '])
            ->assertRedirect(route('hubs.index'));
        $hub = Hub::firstOrFail();
        $this->assertSame('North Hub', $hub->hub_name);

        $this->get(route('drivers.create'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/form')
                ->where('fieldName', 'driver_name')
                ->where('method', 'post'));
        $this->get(route('cargo-types.create'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/form')
                ->where('fieldName', 'cargo_type')
                ->where('method', 'post'));
        $this->get(route('hubs.create'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/form')
                ->where('fieldName', 'hub_name')
                ->where('method', 'post'));

        $this->get(route('drivers.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/index')
                ->where('title', 'Drivers')
                ->where('items.data.0.name', 'Alex Driver'));

        $this->get(route('cargo-types.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/index')
                ->where('title', 'Cargo Types')
                ->where('items.data.0.name', 'Fresh produce'));

        $this->get(route('hubs.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/index')
                ->where('title', 'Hubs')
                ->where('items.data.0.name', 'North Hub'));

        $this->get(route('drivers.edit', $driver))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/form')
                ->where('fieldName', 'driver_name')
                ->where('method', 'put')
                ->where('value', 'Alex Driver'));
        $this->get(route('cargo-types.edit', $cargoType))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/form')
                ->where('fieldName', 'cargo_type')
                ->where('method', 'put')
                ->where('value', 'Fresh produce'));
        $this->get(route('hubs.edit', $hub))
            ->assertInertia(fn (Assert $page) => $page
                ->component('catalog/form')
                ->where('fieldName', 'hub_name')
                ->where('method', 'put')
                ->where('value', 'North Hub'));

        $this->put(route('drivers.update', $driver), ['driver_name' => 'Morgan Driver'])
            ->assertRedirect(route('drivers.index'));
        $this->put(route('cargo-types.update', $cargoType), ['cargo_type' => 'Frozen goods'])
            ->assertRedirect(route('cargo-types.index'));
        $this->put(route('hubs.update', $hub), ['hub_name' => 'South Hub'])
            ->assertRedirect(route('hubs.index'));

        $this->delete(route('drivers.destroy', $driver))->assertRedirect(route('drivers.index'));
        $this->delete(route('cargo-types.destroy', $cargoType))->assertRedirect(route('cargo-types.index'));
        $this->delete(route('hubs.destroy', $hub))->assertRedirect(route('hubs.index'));

        $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
        $this->assertDatabaseMissing('cargo_types', ['id' => $cargoType->id]);
        $this->assertDatabaseMissing('hubs', ['id' => $hub->id]);
    }

    public function test_catalog_entries_used_by_cold_trips_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $driver = Driver::create(['driver_name' => 'Alex Driver']);
        $cargoType = CargoType::create(['cargo_type' => 'Fresh produce']);
        $hub = Hub::create(['hub_name' => 'North Hub']);

        ColdTrip::create([
            'driver_id' => $driver->id,
            'cargo_type_id' => $cargoType->id,
            'departure_hub_id' => $hub->id,
            'destination_hub_id' => $hub->id,
        ]);

        $this->delete(route('drivers.destroy', $driver))
            ->assertRedirect(route('drivers.index'))
            ->assertSessionHas('error');
        $this->delete(route('cargo-types.destroy', $cargoType))
            ->assertRedirect(route('cargo-types.index'))
            ->assertSessionHas('error');
        $this->delete(route('hubs.destroy', $hub))
            ->assertRedirect(route('hubs.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('drivers', ['id' => $driver->id]);
        $this->assertDatabaseHas('cargo_types', ['id' => $cargoType->id]);
        $this->assertDatabaseHas('hubs', ['id' => $hub->id]);
    }

    public function test_catalog_names_must_be_unique_after_trimming(): void
    {
        $this->actingAs(User::factory()->create());
        Driver::create(['driver_name' => 'Alex Driver']);
        CargoType::create(['cargo_type' => 'Fresh produce']);
        Hub::create(['hub_name' => 'North Hub']);

        $this->post(route('drivers.store'), ['driver_name' => ' Alex Driver '])
            ->assertSessionHasErrors('driver_name');
        $this->post(route('cargo-types.store'), ['cargo_type' => ' Fresh produce '])
            ->assertSessionHasErrors('cargo_type');
        $this->post(route('hubs.store'), ['hub_name' => ' North Hub '])
            ->assertSessionHasErrors('hub_name');
    }
}
