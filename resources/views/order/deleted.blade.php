@extends('layouts.main', [
    'title' => $title,
    'activePage' => $active_page
    ])

@section('content')
    <!-- Begin Page Content -->

    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">{{$title}}</h1>
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <h2 class="h4">Vehicles</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Leden Order #</th>
                                    <th>Model</th>
                                    <th>Derivative</th>
                                    <th>Ford Order Number</th>
                                    <th>Orbit Number</th>
                                    <th>Registration</th>
                                    <th>Planned Build Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Customer</th>
                                    <th>Broker Order Ref</th>
                                    <th>Broker</th>
                                    <th>Dealership</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->vehicle?->model }}</td>
                                        <td>{{ $order->vehicle?->derivative }}</td>
                                        <td>{{ $order->vehicle?->ford_order_number }}</td>
                                        <td>{{ $order->vehicle?->orbit_number }}</td>
                                        <td>{{ $order->vehicle?->reg }}</td>
                                        @if ( empty( $order->vehicle?->build_date) || $order->vehicle?->build_date == '0000-00-00 00:00:00')
                                            <td></td>
                                        @else
                                            <td>{{ \Carbon\Carbon::parse($order->vehicle?->build_date ?? '')->format( 'd/m/Y' )}}</td>
                                        @endif
                                        @if ( empty( $order->vehicle?->due_date) || $order->vehicle?->due_date == '0000-00-00 00:00:00')
                                            <td></td>
                                        @else
                                            <td>{{ \Carbon\Carbon::parse($order->vehicle?->due_date ?? '')->format( 'd/m/Y' )}}</td>
                                        @endif
                                        <td>{{ $order->vehicle?->status() }}</td>
                                        <td>
                                            {{ $order->customer->customer_name ?? ''}}
                                        </td>
                                        <td>
                                            {{ $order->broker_ref }}
                                        </td>
                                        <td class="p-0">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item bg-transparent">{{ $order->broker->company_name ?? ''}}</li>
                                                @if( $order->finance_broker )
                                                    <li class="list-group-item bg-transparent">{{ $order->finance_broker->company_name ?? '' }}</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td>{{ $order->dealer->company_name ?? ''}}</td>
                                        <td></td>
                                    </tr>

                                @endforeach
                                
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
