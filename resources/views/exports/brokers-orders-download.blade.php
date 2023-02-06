<table>
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Vehicle</th>
        <th>Due Date</th>
        <th>Holding Code</th>
        <th>Registration</th>
        <th>VIN/Chassis</th>
        <th>Phase</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->broker_ref }}</td>
            <td>{{ $order->customer->customer_name }}</td>
            <td>{{ $order->vehicle->niceName() }}</td>
            <td>
                @if($order->vehicle->vehicle_status === 1 || $order->vehicle->vehicle_status === 15)
                    IN STOCK
                @else
                    {{ $order->vehicle->due_date }}
                @endif
            </td>
            <td>{{ $order->vehicle->ford_order_number }}</td>
            <td>{{ $order->vehicle->reg }}</td>
            <td>{{ $order->vehicle->chassis }}</td>
            <td>
                @if($order->vehicle->vehicle_status === 3 || $order->vehicle->vehicle_status === 5 || $order->vehicle->vehicle_status === 6)
                    Vehicle is in Delivery Phase
                @else
                    Vehicle is in Order Phase
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

