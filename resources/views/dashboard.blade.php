
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cold Chain Delivery Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#0F172A',
                        cyan: '#06B6D4'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-100 text-slate-800">

<div class="min-h-screen flex flex-col md:flex-row">

    <!-- SIDEBAR -->
    <aside class="w-full md:w-64 bg-slate-950 text-white p-6">
        <div class="mb-10">
            <h1 class="text-2xl font-bold text-cyan-400">
                ❄ ColdChain
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Delivery Tracking System
            </p>
        </div>

        <nav class="space-y-3">
            <a href="{{ route('dashboard') }}"
               class="block bg-cyan-600 text-white px-4 py-3 rounded-lg">
                ▦ Dashboard
            </a>

            <a href="{{ route('trucks.index') }}"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                🚚 Truck Management
            </a>

            <a href="{{ route('cold-trips.index') }}"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                📦 Cold Trip Records
            </a>

            <a href="{{ route('trucks.create') }}"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                + Add Truck
            </a>

            <a href="{{ route('cold-trips.create') }}"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                + Add Cold Trip
            </a>
        </nav>

        <div class="mt-12 border-t border-slate-700 pt-5">
            <p class="text-xs text-slate-400">
                Commercial Refrigerated Truck
            </p>
            <p class="text-xs text-slate-400">
                Cold Chain Monitoring
            </p>
        </div>
    </aside>


    <!-- MAIN CONTENT -->
    <main class="flex-1 p-5 md:p-8">

        <!-- HEADER -->
        <header class="flex flex-col sm:flex-row sm:items-center
                       sm:justify-between gap-4 mb-8">

            <div>
                <h2 class="text-3xl font-bold text-slate-900">
                    Dashboard
                </h2>

                <p class="text-slate-500 mt-1">
                    Monitor your refrigerated truck deliveries.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('trucks.create') }}"
                   class="bg-cyan-600 hover:bg-cyan-700 text-white
                          px-4 py-2 rounded-lg">
                    + Add Truck
                </a>

                <a href="{{ route('cold-trips.create') }}"
                   class="bg-slate-900 hover:bg-slate-700 text-white
                          px-4 py-2 rounded-lg">
                    + New Trip
                </a>
            </div>
        </header>


        <!-- STATISTICS CARDS -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5
                        gap-5 mb-8">

            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-cyan-500">
                <p class="text-slate-500 text-sm">Total Trucks</p>
                <h3 class="text-3xl font-bold mt-2">
                    {{ $totalTrucks }}
                </h3>
                <p class="text-xs text-slate-400 mt-2">Registered trucks</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500">
                <p class="text-slate-500 text-sm">Total Cold Trips</p>
                <h3 class="text-3xl font-bold mt-2">
                    {{ $totalTrips }}
                </h3>
                <p class="text-xs text-slate-400 mt-2">Recorded deliveries</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500">
                <p class="text-slate-500 text-sm">Compliant Trips</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">
                    {{ $compliantTrips }}
                </h3>
                <p class="text-xs text-slate-400 mt-2">Within temperature limit</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-red-500">
                <p class="text-slate-500 text-sm">Temperature Alerts</p>
                <h3 class="text-3xl font-bold text-red-600 mt-2">
                    {{ $temperatureAlerts }}
                </h3>
                <p class="text-xs text-slate-400 mt-2">Excursion detected</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-yellow-500">
                <p class="text-slate-500 text-sm">Pending Trips</p>
                <h3 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $pendingTrips }}
                </h3>
                <p class="text-xs text-slate-400 mt-2">Awaiting temperature logs</p>
            </div>

        </section>


        <!-- TEMPERATURE ALERTS -->
        <section class="bg-white rounded-xl shadow-sm p-6 mb-8">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">
                        Temperature Excursion Alerts
                    </h3>
                    <p class="text-sm text-slate-500">
                        Trips exceeding the allowed temperature.
                    </p>
                </div>

                <span class="bg-red-100 text-red-700 px-3 py-1
                             rounded-full text-sm font-semibold">
                    {{ $temperatureAlerts }} Alerts
                </span>
            </div>

            @forelse ($alertTrips as $trip)

                <div class="border border-red-200 bg-red-50 rounded-lg
                            p-4 mb-3">

                    <div class="flex flex-col sm:flex-row
                                sm:justify-between gap-3">

                        <div>
                            <h4 class="font-bold text-red-700">
                                {{ $trip->truck->truck_plate ?? 'No truck assigned' }}
                            </h4>

                            <p class="text-sm text-slate-600">
                                Driver:
                                {{ $trip->driver->driver_name ?? 'N/A' }}
                            </p>

                            <p class="text-sm text-slate-600">
                                Cargo:
                                {{ $trip->cargoType->cargo_type ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="text-sm">
                            <p>
                                Required:
                                <strong>{{ $trip->required_temp_c }}°C</strong>
                            </p>

                            <p>
                                Maximum:
                                <strong class="text-red-600">
                                    {{ $trip->max_logged_temp ?? 'N/A' }}°C
                                </strong>
                            </p>

                            <span class="inline-block mt-2 bg-red-600
                                         text-white text-xs px-3 py-1
                                         rounded-full">
                                Temperature Excursion Alert
                            </span>
                        </div>

                    </div>
                </div>

            @empty

                <div class="bg-green-50 text-green-700 p-5 rounded-lg">
                    ✓ No temperature excursion alerts recorded.
                </div>

            @endforelse

        </section>


        <!-- RECENT COLD TRIPS -->
        <section class="bg-white rounded-xl shadow-sm p-6">

            <div class="flex flex-col sm:flex-row
                        sm:justify-between sm:items-center gap-3 mb-5">

                <div>
                    <h3 class="text-xl font-bold text-slate-900">
                        Recent Cold Trips
                    </h3>

                    <p class="text-sm text-slate-500">
                        Latest refrigerated truck delivery records.
                    </p>
                </div>

                <a href="{{ route('cold-trips.index') }}"
                   class="text-cyan-700 font-semibold hover:underline">
                    View All Trips →
                </a>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="p-3">Truck Plate</th>
                            <th class="p-3">Driver</th>
                            <th class="p-3">Cargo</th>
                            <th class="p-3">Route</th>
                            <th class="p-3">Required Temp</th>
                            <th class="p-3">Maximum Temp</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($recentTrips as $trip)

                            <tr class="hover:bg-slate-50">

                                <td class="p-3 font-semibold">
                                    {{ $trip->truck->truck_plate ?? 'N/A' }}
                                </td>

                                <td class="p-3">
                                    {{ $trip->driver->driver_name ?? 'N/A' }}
                                </td>

                                <td class="p-3">
                                    {{ $trip->cargoType->cargo_type ?? 'N/A' }}
                                </td>

                                <td class="p-3">
                                    {{ $trip->departureHub->hub_name ?? 'N/A' }}
                                    →
                                    {{ $trip->destinationHub->hub_name ?? 'N/A' }}
                                </td>

                                <td class="p-3">
                                    {{ $trip->required_temp_c }}°C
                                </td>

                                <td class="p-3">
                                    {{ $trip->max_logged_temp !== null
                                        ? $trip->max_logged_temp . '°C'
                                        : 'Pending' }}
                                </td>

                                <td class="p-3">

                                    @if ($trip->temp_compliance_status === 'Compliant')

                                        <span class="bg-green-100 text-green-700
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold">
                                            Compliant
                                        </span>

                                    @elseif ($trip->temp_compliance_status === 'Temperature Excursion Alert')

                                        <span class="bg-red-100 text-red-700
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold">
                                            Alert
                                        </span>

                                    @else

                                        <span class="bg-yellow-100 text-yellow-700
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold">
                                            Pending
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center p-8 text-slate-500">
                                    No cold trip records found.
                                    Add your first delivery trip.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>

        </section>


        <!-- FOOTER -->
        <footer class="text-center text-slate-400 text-sm mt-8">
            Commercial Refrigerated Truck Cold Chain Delivery Tracker
            <br>
            Temperature Monitoring and Delivery Management System
        </footer>

    </main>
</div>

</body>
</html>
