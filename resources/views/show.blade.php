
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cold Trip Details</title>
</head>
<body>
    <h1>Cold Trip Details #{{ $trip->id }}</h1>

    <p><strong>Truck:</strong> {{ $trip->truck->truck_plate }}</p>
    <p><strong>Driver:</strong> {{ $trip->driver->driver_name }}</p>
    <p><strong>Cargo:</strong> {{ $trip->cargoType->cargo_type }}</p>

    <p><strong>Departure:</strong> {{ $trip->departureHub->hub_name }}</p>
    <p><strong>Destination:</strong> {{ $trip->destinationHub->hub_name }}</p>

    <p><strong>Required Temperature:</strong>
        {{ $trip->required_temp_c }} °C
    </p>

    <p><strong>Minimum Temperature:</strong>
        {{ $trip->min_logged_temp ?? 'No reading' }} °C
    </p>

    <p><strong>Maximum Temperature:</strong>
        {{ $trip->max_logged_temp ?? 'No reading' }} °C
    </p>

    <p><strong>Dispatch:</strong>
        {{ $trip->dispatch_time->format('Y-m-d H:i') }}
    </p>

    <p><strong>Arrival:</strong>
        {{ $trip->arrival_time?->format('Y-m-d H:i') ?? 'In progress' }}
    </p>

    <p><strong>Compliance Status:</strong>
        {{ $trip->temp_compliance_status }}
    </p>

    <a href="{{ route('cold-trips.edit', $trip) }}">Edit Trip</a>
    |
    <a href="{{ route('cold-trips.index') }}">Back to Trips</a>
</body>
</html>
