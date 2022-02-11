<table>
    <thead>
    <tr>
        <th>Leden Order #</th>
        <th>Customer</th>
        <th>Model</th>
        <th>Vehicle Type</th>
        <th>Derivative</th>
        <th>Engine</th>
        <th>Colour</th>
        <th>Chassis</th>
        <th>Registration</th>
        <th>Broker</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>
                {{ $order->customer->name() }}
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->model}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->type}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->derivative}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->engine}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->colour}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->chassis}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->reg}}
                @endif
            </td>
            <td>
                {{ $order->broker->company_name }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
