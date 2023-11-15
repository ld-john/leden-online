<table>
    <thead>
    <tr>
        <th>Order Number</th>
        <th>Customer Name</th>
        <th>Ford Order Number</th>
        <th>Orbit Number</th>
        <th>Vehicle Type</th>
        <th>Make</th>
        <th>Model</th>
        <th>Derivative</th>
        <th>Engine</th>
        <th>Transmission</th>
        <th>Colour</th>
        <th>Chassis Prefix</th>
        <th>Chassis</th>
        <th>Registration</th>
        <th>Planned Build Date</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Broker</th>
        <th>Broker Reference</th>
        <th>Factory Fit Options</th>
        <th>Dealer Fit Options</th>
        <th>Website Location</th>
        <th>Exclusion</th>
    </tr>
    </thead>
    <tbody>
    @foreach($vehicles as $vehicle)
        <tr>
            <td>{{ $vehicle->order?->id }}</td>
            <td>{{ $vehicle->order?->customer->name() }}</td>
            <td>{{ $vehicle->ford_order_number }}</td>
            <td>{{ $vehicle->orbit_number }}</td>
            <td>{{ $vehicle->type }}</td>
            <td>{{ $vehicle->manufacturer?->name }}</td>
            <td>{{ $vehicle->model }}</td>
            <td>{{ $vehicle->derivative }}</td>
            <td>{{ $vehicle->engine }}</td>
            <td>{{ $vehicle->transmission }}</td>
            <td>{{ $vehicle->colour }}</td>
            <td>{{ $vehicle->chassis_prefix }}</td>
            <td>{{ $vehicle->chassis }}</td>
            <td>{{ $vehicle->reg }}</td>
            @if ( empty( $vehicle->build_date) || $vehicle->build_date == '0000-00-00 00:00:00')
                <td></td>
            @else
                <td>{{ \Carbon\Carbon::parse($vehicle->build_date ?? '')->format( 'd/m/Y' )}}</td>
            @endif
            @if(empty($vehicle->due_date || $vehicle->due_date == '0000-00-00 00:00:00'))
                <td></td>
            @else
                <td>{{ \Carbon\Carbon::parse($vehicle->due_date)->format('d/m/Y') }}</td>
            @endif
            <td>{{ $vehicle->status() }}</td>
            <td>
                {{ $vehicle->broker?->company_name }}
            </td>
            <td>{{ $vehicle->order?->broker_ref }}</td>
            <td>
                {{ implode(', ', $vehicle->factoryFitOptions()->pluck('option_name')->toArray()) }}
            </td>
            <td>
                {{ implode(', ', $vehicle->dealerFitOptions()->pluck('option_name')->toArray()) }}
            </td>
            <td>
                @php
                    $button = $vehicle->websiteLocation();
                @endphp
                {{ $button['location'] }}
            </td>
            @if($vehicle->order?->exception)
                <td>Yes</td>
            @else
                <td>No</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
