@extends('layouts.main', [
    'title' => 'View Vehicle',
    'activePage' => 'view-vehicle'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">View Vehicle - #{{ $vehicle->id }}</h1>

                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Details</h6>
                        <div>
                            @if ($vehicle->vehicle_status == 1)
                                <strong>Vehicle Status:</strong> <span class="badge badge-success">Available</span>
                            @elseif ($vehicle->vehicle_status == 2)
                                <strong>Vehicle Status:</strong> <span class="badge badge-warning">Reserved</span>
                            @elseif ($vehicle->vehicle_status == 3 || $vehicle->vehicle_status == 4 || $vehicle->vehicle_status == 6)
                                <strong>Vehicle Status:</strong> <span class="badge badge-info">
                            @if ($vehicle->vehicle_status == 3)
                                        Ready for delivery
                                    @elseif ($vehicle->vehicle_status == 4)
                                        Factory Order
                                    @elseif($vehicle->vehicle_status == 6)
                                        Delivery Booked
                                    @endif
                        </span>
                            @elseif ($vehicle->vehicle_status == 7)
                                <strong>Vehicle Status:</strong> <span class="badge badge-secondary">Completed Order</span>
                            @else
                                <strong>Vehicle Status:</strong> <span class="badge badge-secondary">Status Unavailable</span>
                            @endif
                            @if( isset( $vehicle->order->id ) )
                                <span class="badge badge-success">Vehicle is on order - <a href="{{route('order.show', $vehicle->order->id)}}">View Order</a></span>
                            @else
                                <span class="badge badge-success">Vehicle is available for order - <a href="{{route('create_order')}}">Reserve</a> </span>
                            @endif
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Make</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->manufacturer->name }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Model</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->model }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Type</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->type }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Derivative</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->derivative }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Engine</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->engine }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Transmission</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->transmission }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Reg</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->reg }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Colour</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->colour }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Body</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->body }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Trim</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->trim }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Chassis Prefix</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->chassis_prefix }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Chassis</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->chassis }}</p>
                            </div>
                        </div>

                        @if (Auth::user()->role != 'broker')
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Model Year</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $vehicle->model_year }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Extra Vehicle Options</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p>Factory Fitted Options</p>
                            </div>
                            <div class="col-md-4">
                                <ul>
                                    @forelse($vehicle->getFitOptions('factory') as $option)
                                        <li>{{$option->option_name}} - £{{$option->option_price}}</li>
                                    @empty
                                        <li>No options selected</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <p>Dealer Added Options</p>
                            </div>
                            <div class="col-md-4">
                                <ul>
                                    @forelse($vehicle->getFitOptions('dealer') as $option)
                                        <li>{{$option->option_name}} - £{{$option->option_price}}</li>
                                    @empty
                                        <li>No options selected</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
