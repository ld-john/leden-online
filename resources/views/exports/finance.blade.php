<table>
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Finance Type</th>
        <th>Maintenance</th>
        <th>Term</th>
        <th>Initial Payment</th>
        <th>Terminal Pause</th>
        <th>Mileage</th>
        <th>Rental</th>
        <th>Maintenance Rental</th>
        <th>Renewal Date</th>
        <th>Customer</th>
        <th>Registration Company</th>
        <th>Invoice Company</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->FinanceType?->option }}</td>
            <td>{{ $order->Maintenance?->option }}</td>
            <td>{{ $order->Term?->option }}</td>
            <td>{{ $order->InitialPayment?->option }}</td>
            @if($order->terminal_pause)
                <td>Yes</td>
            @else
                <td>No</td>
            @endif
            <td>{{ $order->Mileage?->option }}</td>
            <td>£{{ $order->rental }}</td>
            <td>£{{$order->maintenance_rental }}</td>
            <td>{{ \Carbon\Carbon::parse($order->renewal_date)->format('d/m/Y') }}</td>
            <td>{{ $order->customer->name() }}</td>
            <td>{{ $order->registration_company?->company_name }}</td>
            <td>{{ $order->invoice_company?->company_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

