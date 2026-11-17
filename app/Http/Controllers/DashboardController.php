<?php

namespace App\Http\Controllers;

use App\Models\ColdTrip;
use App\Models\Truck;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $trips = ColdTrip::query()
            ->with(['truck', 'driver', 'cargoType', 'departureHub', 'destinationHub']);

        return Inertia::render('dashboard', [
            'totalTrucks' => Truck::count(),
            'totalTrips' => (clone $trips)->count(),
            'compliantTrips' => (clone $trips)->where('temp_compliance_status', 'Accept')->count(),
            'temperatureAlerts' => (clone $trips)
                ->whereRaw('max_logged_temp IS NOT NULL AND required_temp_c IS NOT NULL AND max_logged_temp > required_temp_c + 2')
                ->count(),
            'pendingTrips' => (clone $trips)->where('temp_compliance_status', 'Pending')->count(),
            'alertTrips' => (clone $trips)
                ->whereRaw('max_logged_temp IS NOT NULL AND required_temp_c IS NOT NULL AND max_logged_temp > required_temp_c + 2')
                ->latest('dispatch_time')
                ->take(5)
                ->get(),
            'recentTrips' => (clone $trips)
                ->latest('dispatch_time')
                ->take(8)
                ->get(),
        ]);
    }
}
