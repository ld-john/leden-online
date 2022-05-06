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
                        <div class="d-flex align-items-center"><strong>Vehicle Status:</strong> <span class="badge badge-primary ml-3">{{ $vehicle->status() }}</span>
                            @if( isset( $vehicle->order->id ) )
                                <span class="badge badge-success ml-3">Vehicle is on order - <a href="{{route('order.show', $vehicle->order->id)}}">View Order</a></span>
                            @else
                                @can('admin')
                                    <span class="badge badge-success ml-3">Vehicle is available for order - <a class="text-white" href="{{route('create_order')}}">Reserve</a> </span>
                                @else
                                    <span class="badge badge-success ml-3">Vehicle is available for order - Contact Leden for details</span>
                                @endcan
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
                                <p class="font-weight-bold">{{ $vehicle->manufacturer->name ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Model</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->model ?? '--'}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Orbit Number</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->orbit_number ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Ford Order Ref</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->ford_order_number ?? '--'}}</p>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Type</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->type ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Derivative</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->derivative ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Engine</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->engine ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Transmission</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->transmission ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Reg</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->reg ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Colour</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->colour ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Trim</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->trim ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Chassis Prefix</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->chassis_prefix ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Chassis</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $vehicle->chassis ?? '--'}}</p>
                            </div>
                        </div>

                        @if (Auth::user()->role != 'broker')
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Model Year</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $vehicle->model_year ?? '--'}}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Dealer</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $vehicle->dealer->company_name ?? '--'}}</p>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <div class="col-md-2">
                                <p>Planned Build Date</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">
                                    @if( isset ($vehicle->build_date) && ( $vehicle->build_date != '0000-00-00 00:00:00') )
                                        {{ date('d/m/Y', strtotime($vehicle->build_date)) }}
                                    @else
                                        TBC
                                    @endif
                                </p>
                            </div>
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
                                        <li>{{$option->model}}-{{$option->model_year}}MY-{{$option->option_name}} - £{{number_format($option->option_price, 2, '.', '')}}</li>
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
                                        <li>{{$option->model}}-{{$option->model_year}}MY-{{$option->option_name}} - £{{number_format($option->option_price, 2, '.', '')}}</li>
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
