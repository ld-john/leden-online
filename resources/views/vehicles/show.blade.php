@extends('layouts.main', [
    'title' => 'View Vehicle',
    'activePage' => 'view-vehicle'
    ])

@section('content')
    <div class="container-fluid force-min-height">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">View Vehicle - #{{ $vehicle->id }}</h1>
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Details</h6>
                        <div class="d-flex align-items-center"><strong>Vehicle Status:</strong>
                            <span class="badge bg-primary ms-3">
                                {{ $vehicle->vehicle_status->label() }}
                                @if($vehicle->vehicle_status->isInStock() && $vehicle->in_stock_at)
                                    ({{ $vehicle->time_in_stock }})
                                @endif
                            </span>
                            @if( $vehicle->reservation )
                                <span class="badge bg-success ms-3">Reserved</span>
                            @elseif($vehicle->order )
                                <span class="badge bg-danger ms-3">On Order</span>
                            @endif
                        </div>
                    </div>
                    {{-- Vehicle Details Body --}}
                    <div class="card-body">
                        <div class="row">
                            <x-detail-pair label="Vehicle Make" :value="$vehicle->manufacturer?->name" />
                            <x-detail-pair label="Vehicle Model" :value="$vehicle->model" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Orbit Number" :value="$vehicle->orbit_number" />
                            <x-detail-pair label="Ford Order Ref" :value="$vehicle->ford_order_number" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Vehicle Type" :value="$vehicle->type" />
                            <x-detail-pair label="Vehicle Derivative" :value="$vehicle->derivative" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Vehicle Engine" :value="$vehicle->engine" />
                            <x-detail-pair label="Vehicle Transmission" :value="$vehicle->transmission" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Vehicle Reg" :value="$vehicle->reg" />
                            <x-detail-pair label="Vehicle Colour" :value="$vehicle->colour" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <x-detail-pair label="Vehicle Trim" :value="$vehicle->trim" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Chassis Prefix" :value="$vehicle->chassis_prefix" />
                            <x-detail-pair label="Chassis" :value="$vehicle->chassis" />
                        </div>

                        @cannot('broker')
                            <div class="row">
                                <x-detail-pair label="Model Year" :value="$vehicle->model_year" />
                                <x-detail-pair label="Dealer" :value="$vehicle->dealer?->company_name" />
                            </div>
                        @endcannot
                        @php
                            $formattedBuildDate = $vehicle->build_date?->format('d/m/Y') ?? 'TBC';
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <x-detail-pair label="Planned Build Date" :value="$formattedBuildDate" />
                        </div>
                    </div>
                    {{-- Extra Options Section --}}
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
                    @can('admin')
                        @if($vehicle->tacticalOffers)
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-l-blue">Tactical Offers - will be applied if still valid when order is placed.</h6>
                            </div>
                            <div class="card-body">
                                <ul>
                                    @foreach($vehicle->tacticalOffers as $offer)
                                        <li>{{ $offer->name }} - £{{ number_format($offer->value, 2) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endcan
                    @can('admin')
                        @livewire('comment-box', ['commentable_id' => $vehicle->id, 'commentable_type' => 'vehicle'])
                    @endcan
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
