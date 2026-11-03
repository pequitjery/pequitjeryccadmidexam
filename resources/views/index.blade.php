
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trucks - Cold Chain Tracker</title>
</head>
<body>
    <h1>Refrigerated Trucks</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('trucks.create') }}">+ Register Truck</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>License Plate</th>
                <th>Total Trips</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trucks as $truck)
                <tr>
                    <td>{{ $truck->id }}</td>
                    <td>{{ $truck->truck_plate }}</td>
                    <td>{{ $truck->cold_trips_count ?? $truck->coldTrips->count() }}</td>
                    <td>
                        <a href="{{ route('trucks.show', $truck) }}">View</a>
                        |
                        <a href="{{ route('trucks.edit', $truck) }}">Edit</a>
                        |
                        <form action="{{ route('trucks.destroy', $truck) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this truck?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No trucks registered yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $trucks->links() }}
</body>
</html>
