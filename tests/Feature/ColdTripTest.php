<?php

namespace Tests\Feature;

use App\Models\ColdTrip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ColdTripTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_create_a_cold_trip(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('cold-trips.store'), $this->tripData())
            ->assertRedirect(route('cold-trips.index'));

        $this->get(route('cold-trips.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('cold-trips/index')
                ->where('flash.success', 'Cold trip created successfully.')
                ->has('trips.data', 1));

        $trip = ColdTrip::with(['truck', 'driver', 'cargoType', 'departureHub', 'destinationHub'])->firstOrFail();

        $this->assertSame('ABC123', $trip->truck->truck_plate);
        $this->assertSame('Compliant', $trip->temp_compliance_status);
        $this->assertSame('Fresh produce', $trip->cargoType->cargo_type);
    }

    public function test_invalid_temperature_ranges_are_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('cold-trips.store'), [
                ...$this->tripData(),
                'min_logged_temp' => 6,
                'max_logged_temp' => 2,
            ])
            ->assertSessionHasErrors('min_logged_temp');

        $this->assertDatabaseCount('cold_trips', 0);
    }

    public function test_trip_can_be_updated_and_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('cold-trips.store'), $this->tripData());
        $trip = ColdTrip::firstOrFail();

        $this->put(route('cold-trips.update', $trip), [
            ...$this->tripData(),
            'truck_plate' => 'xyz789',
            'max_logged_temp' => 8,
        ])->assertRedirect(route('cold-trips.index'));

        $trip->refresh();
        $this->assertSame('XYZ789', $trip->truck->truck_plate);
        $this->assertSame('Temperature Excursion Alert', $trip->temp_compliance_status);

        $this->delete(route('cold-trips.destroy', $trip))
            ->assertRedirect(route('cold-trips.index'));

        $this->assertDatabaseCount('cold_trips', 0);
    }

    public function test_dashboard_receives_live_trip_statistics(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('cold-trips.store'), $this->tripData());

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('dashboard')
                ->where('totalTrucks', 1)
                ->where('totalTrips', 1)
                ->where('compliantTrips', 1)
                ->where('temperatureAlerts', 0)
                ->where('pendingTrips', 0)
                ->has('recentTrips', 1));
    }

    private function tripData(): array
    {
        return [
            'truck_plate' => 'abc123',
            'driver_name' => 'Alex Driver',
            'cargo_type' => 'Fresh produce',
            'departure_hub' => 'North Hub',
            'destination_hub' => 'South Hub',
            'required_temp_c' => 4,
            'min_logged_temp' => 1,
            'max_logged_temp' => 3,
            'dispatch_time' => '2026-11-03T08:00',
            'arrival_time' => '',
        ];
    }
}
