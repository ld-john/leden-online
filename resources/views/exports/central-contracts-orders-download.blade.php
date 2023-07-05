<table>
    <thead>
    <tr>
        <th>Order Ref</th>
        <th>Due Date</th>
        <th>Registration</th>
        <th>VIN</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->broker_ref }}</td>
            <td>
                @if($order->vehicle->vehicle_status === '1' || $order->vehicle->vehicle_status === '15')
                    IN STOCK
                @else
                    {{ $order->vehicle->due_date }}
                @endif
            </td>
            <td>{{ $order->vehicle->reg }}</td>
            <td>{{ $order->vehicle->chassis }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

