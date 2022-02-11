

<table>
    <thead>
    <tr>
        <th>Leden Order Number</th>
        <th>Customer Name</th>
        <th>Ford Order Number</th>
        <th>Orbit Number</th>
        <th>Vehicle Type</th>
        <th>Chassis</th>
        <th>Registration</th>
        <th>Due Date</th>
        <th>Broker</th>
        <th>Broker Reference</th>
    </tr>
    </thead>
    <tbody>
    @foreach($vehicles as $vehicle)

        <tr>
            <td>{{ $vehicle->order->id ?? '' }}</td>
            <td>
                @if($vehicle->order)
                    {{ $vehicle->order->customer->name() }}
                @else
                    Vehicle unsold
                @endif
            </td>
            <td>{{ $vehicle->order->order_ref ?? '' }}</td>
            <td>{{ $vehicle->orbit_number }}</td>
            <td>{{ $vehicle->type }}</td>
            <td>{{ $vehicle->chassis }}</td>
            <td>{{ $vehicle->reg }}</td>
            <td>
                @if($vehicle->order)
                    @if(Carbon\Carbon::parse($vehicle->order->due_date)->format('y') === '-1')
                        N/a
                    @else
                        {{ Carbon\Carbon::parse($vehicle->order->due_date)->format('d/m/y') }}
                    @endif
                @else
                    N/a
                @endif
            </td>
            <td>
                @if($vehicle->order)
                    {{ $vehicle->order->broker->company_name }}
                @else
                    No Broker found
                @endif

            </td>
            <td>{{ $vehicle->order->broker_ref ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
