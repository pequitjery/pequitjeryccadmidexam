<?php

namespace Tests\Feature;

use App\Models\Truck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TruckTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_the_truck_list(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('trucks.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('trucks/index')
                ->has('trucks.data'));
    }

    public function test_truck_can_be_created_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('trucks.store'), ['truck_plate' => 'abc123'])
            ->assertRedirect(route('trucks.index'));

        $truck = Truck::firstOrFail();
        $this->assertSame('ABC123', $truck->truck_plate);

        $this->put(route('trucks.update', $truck), ['truck_plate' => 'xyz789'])
            ->assertRedirect(route('trucks.index'));
        $this->assertSame('XYZ789', $truck->refresh()->truck_plate);

        $this->delete(route('trucks.destroy', $truck))
            ->assertRedirect(route('trucks.index'));
        $this->assertDatabaseCount('trucks', 0);
    }

    public function test_truck_with_trips_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('cold-trips.store'), [
            'truck_plate' => 'ABC123',
            'driver_name' => 'Alex Driver',
            'cargo_type' => 'Fresh produce',
            'departure_hub' => 'North Hub',
            'destination_hub' => 'South Hub',
            'required_temp_c' => 4,
            'dispatch_time' => '2026-11-03T08:00',
        ]);
        $truck = Truck::firstOrFail();

        $this->delete(route('trucks.destroy', $truck))
            ->assertRedirect(route('trucks.index'))
            ->assertSessionHas('error', 'This truck has cold trips and cannot be deleted.');

        $this->assertDatabaseHas('trucks', ['id' => $truck->id]);
        $this->assertDatabaseCount('cold_trips', 1);
    }

    public function test_truck_details_include_trip_history(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('cold-trips.store'), [
            'truck_plate' => 'ABC123',
            'driver_name' => 'Alex Driver',
            'cargo_type' => 'Fresh produce',
            'departure_hub' => 'North Hub',
            'destination_hub' => 'South Hub',
            'required_temp_c' => 4,
            'dispatch_time' => '2026-11-03T08:00',
        ]);

        $truck = Truck::firstOrFail();
        $this->get(route('trucks.show', $truck))
            ->assertInertia(fn (Assert $page) => $page
                ->component('trucks/show')
                ->where('truck.truck_plate', 'ABC123')
                ->has('truck.cold_trips', 1));
    }
}
