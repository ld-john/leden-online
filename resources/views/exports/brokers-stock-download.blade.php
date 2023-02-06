<table>
    <thead>
    <tr>
        <th>Order Ref</th>
        <th>Make</th>
        <th>Model</th>
        <th>Derivative</th>
        <th>Transmission</th>
        <th>Vehicle Type</th>
        <th>Colour</th>
        <th>Due Date</th>
        <th>Registered</th>
        <th>Engine</th>
        <th>Extras</th>
        <th>Chassis</th>
        <th>Registration</th>
        <th>Ford Order Number</th>
        <th>Planned Build Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($vehicles as $vehicle)
        <tr>
            <td>{{ $vehicle->id }}</td>
            <td>{{ $vehicle->manufacturer->name }}</td>
            <td>{{ strtoupper($vehicle->model) }}</td>
            <td>{{ strtoupper($vehicle->derivative) }}</td>
            <td>{{ $vehicle->transmission }}</td>
            <td>{{ $vehicle->simplified_type() }}</td>
            <td>{{ strtoupper($vehicle->colour) }}</td>
            <td>{{ $vehicle->due_date }}</td>
            <td>{{ $vehicle->vehicle_registered_on }}</td>
            <td>{{ $vehicle->engine }}</td>
            <td>{{ count($vehicle->factoryFitOptions()) }}</td>
            <td>{{ $vehicle->chassis }}</td>
            <td>{{ $vehicle->reg }}</td>
            <td>{{ $vehicle->ford_order_number }}</td>
            <td>{{ $vehicle->build_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

