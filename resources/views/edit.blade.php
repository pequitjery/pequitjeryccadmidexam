
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Truck</title>
</head>
<body>
    <h1>Edit Truck</h1>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('trucks.update', $truck) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Truck License Plate:</label>
        <input type="text" name="truck_plate"
               value="{{ old('truck_plate', $truck->truck_plate) }}"
               required>

        <button type="submit">Update Truck</button>
    </form>

    <a href="{{ route('trucks.index') }}">Back to Trucks</a>
</body>
</html>
