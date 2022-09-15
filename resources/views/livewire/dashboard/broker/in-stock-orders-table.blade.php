<div>
    <div class="d-flex justify-content-between">
        <div class="w-25 pb-3 d-flex align-items-center">
            Show
            <select wire:model="paginate" class="form-control mx-2">
                <option value='10'>10</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
            </select>
            entries
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>Leden ID</th>
                <th>Orbit Number</th>
                <th>Broker Ref</th>
                <th>Customer</th>
                <th>Make</th>
                <th>Model</th>
                <th>Delivery Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach( $orders as $order )
                <tr>
                    <td>{{ $order->id ?? '--' }}
                    <td>{{ $order->vehicle->orbit_number ?? '--'}}</td>
                    <td>{{ $order->broker_ref ?? '--' }}</td>
                    <td>{{ $order->customer->name() }}</td>
                    <td>{{ $order->vehicle->manufacturer->name ?? '--' }}</td>
                    <td>{{ $order->vehicle->model ?? '--' }}</td>
                    @if ( empty( $order->delivery_date) || $order->delivery_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($order->delivery_date ?? '')->format( 'd/m/Y' )}}</td>
                    @endif
                    <td width="120px">
                        <a data-toggle="tooltip" title="View" href="{{route('order.show', [$order->id])}}" class="btn btn-primary"><i class="far fa-eye"></i></a>
                        @if($order->delivery_date && $order->delivery_date !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_status === 1)
                            <a data-toggle="tooltip" title="Request Delivery" href="{{ route('delivery.create', $order->id) }}" class="btn btn-primary"><i class="fa-solid fa-truck"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            @if(!$orders->isEmpty())
                <p>Showing {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{$orders->total()}}</p>
            @endif
            <div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
