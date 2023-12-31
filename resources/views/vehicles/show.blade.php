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
                        <div class="d-flex align-items-center"><strong>Vehicle Status:</strong>
                            <span class="badge bg-primary ms-3">{{ $vehicle->status() }}</span>
                            @if( $vehicle->reservation )
                                <span class="badge bg-success ms-3">Reserved</span>
                            @elseif($vehicle->order )
                                <span class="badge bg-danger ms-3">On Order</span>
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
                                    @foreach($vehicle->factoryFitOptions() as $fitOption)
                                        <li>{{ $fitOption->factoryOptionName }} - £{{ number_format($fitOption->option_price, 2, '.', '') }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <p>Dealer Added Options</p>
                            </div>
                            <div class="col-md-4">
                                <ul>
                                    @foreach($vehicle->dealerFitOptions() as $fitOption)
                                        <li>{{ $fitOption->dealerOptionName }} - £{{ number_format($fitOption->option_price, 2, '.', '') }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @livewire('comment-box', ['commentable_id' => $vehicle->id, 'commentable_type' => 'vehicle'])
                    <div class="card-footer text-right">
                        <div class="row">
                            <div class="col-md-12">
                                @if( $vehicle->order || $vehicle->reservation )

                                @else
                                    @can('admin')
                                        <a class="btn btn-warning" href="{{ route('order.reserve', $vehicle->id ) }}">Place Order with Vehicle #{{ $vehicle->id }}</a>
                                    @endcan
                                @endif
                                @if (!$vehicle->order)
                                    @can('broker')
                                        @if($vehicle->reservation)
                                            @if (Auth::user()->company_id === $vehicle->reservation?->broker_id)
                                                {{ $vehicle->reservation->customer->fullName }} has reserved this vehicle until {{ \Carbon\Carbon::parse($vehicle->reservation->expiry_date)->format('d/m/Y') }}
                                            @else
                                                Vehicle is on order or reserved by a different company
                                            @endif
                                        @else
                                            @if ($reservation_allowed)
                                                <a class="btn btn-secondary" href="{{ route('reservation.create', $vehicle->id ) }}">Reserve Vehicle #{{ $vehicle->id }}</a>
                                            @endif
                                                <a class="btn btn-warning" href="{{ route('vehicle.request-otr', $vehicle->id) }}">Request OTR for Vehicle #{{ $vehicle->id }}</a>
                                        @endif
                                    @endcan
                                    @can('admin')
                                        <a class="btn btn-primary" href="{{ route('reservation.admin', $vehicle->id) }}">Reserve this Vehicle</a>
                                        @if ($vehicle->reservation)
                                            {{ $vehicle->reservation?->customer->fullName }} from {{ $vehicle->reservation?->company->company_name }} has reserved this vehicle until {{ \Carbon\Carbon::parse($vehicle->reservation?->expiry_date)->format('d/m/Y') }}
                                        @endif
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
