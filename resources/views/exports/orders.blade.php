<table>
    <thead>
    <tr>
        <th>Leden Order ID</th>
        <th>Make</th>
        <th>Model</th>
        <th>Orbit Number</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->manufacturer->name}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->model}}
                @endif
            </td>
            <td>
                @if( $order->vehicle )
                    {{$order->vehicle->orbit_number}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
