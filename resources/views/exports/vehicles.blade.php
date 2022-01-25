<table>
    <thead>
    <tr>
        <th>Leden ID</th>
        <th>Make</th>
        <th>Model</th>
    </tr>
    </thead>
    <tbody>
    @foreach($vehicles as $vehicle)
        <tr>
            <td>{{ $vehicle->id }}</td>
            <td>{{ $vehicle->manufacturer->name }}</td>
            <td>{{ $vehicle->model }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
