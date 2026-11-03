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
            'compliantTrips' => (clone $trips)->where('temp_compliance_status', 'Compliant')->count(),
            'temperatureAlerts' => (clone $trips)->where('temp_compliance_status', 'Temperature Excursion Alert')->count(),
            'pendingTrips' => (clone $trips)->where('temp_compliance_status', 'Pending')->count(),
            'alertTrips' => (clone $trips)
                ->where('temp_compliance_status', 'Temperature Excursion Alert')
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
