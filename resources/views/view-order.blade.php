@extends('layouts.main', [
    'title' => 'View Order',
    'activePage' => 'view-order'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-10">
        <h1 class="h3 mb-4 text-gray-800">View Order - #{{ $order->id }}</h1>

        <div class="card shadow mb-4">
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Details</h6>
                <div>
                    @if ($order->vehicle_status == 1)
                    <strong>Vehicle Status:</strong> <span class="badge badge-success">Available</span>
                    @elseif ($order->vehicle_status == 2)
                    <strong>Vehicle Status:</strong> <span class="badge badge-warning">Reserved</span>
                    @elseif ($order->vehicle_status == 3 || $order->vehicle_status == 4 || $order->vehicle_status == 6)
                    <strong>Vehicle Status:</strong> <span class="badge badge-info">
                        @if ($order->vehicle_status == 3)
                            Ready for delivery
                        @elseif ($order->vehicle_status == 4)
                            Factory Order
                        @elseif($order->vehicle_status == 6)
                            Delivery Booked
                        @endif
                    </span>
                    @elseif ($order->vehicle_status == 7)
                    <strong>Vehicle Status:</strong> <span class="badge badge-secondary">Completed Order</span>
                    @else
                    <strong>Vehicle Status:</strong> <span class="badge badge-secondary">Status Unavailable</span>
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
                        <p class="font-weight-bold">{{ $order->vehicle_make }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Vehicle Model</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_model }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <p>Vehicle Type</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_type }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Vehicle Derivative</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_derivative }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <p>Vehicle Engine</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_engine }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Vehicle Transmission</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_trans }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <p>Vehicle Reg</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_reg }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Vehicle Colour</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_colour }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <p>Vehicle Body</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_body }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Vehicle Trim</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->vehicle_trim }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <p>Chassis Prefix</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->chassis_prefix }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Chassis</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->chassis }}</p>
                    </div>
                </div>

                <div class="row">
                    @if (Helper::roleCheck(Auth::user()->id)->role != 'broker')
                    <div class="col-md-2">
                        <p>Model Year</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->model_year }}</p>
                    </div>
                    @endif
                    <div class="col-md-2">
                        @if ($order->admin_accepted == 1 && $order->dealer_accepted == 1 && $order->broker_accepted == 1)
                        <p>Delivery Date</p>
                        @else
                        <p>Proposed Delivery Date</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">
                            @if($order->delivery_date)
                            {{ date('d/m/Y', strtotime($order->delivery_date)) }}
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
                        @foreach ($order->factoryOptions()->get() as $factory_option)
                            <div class="font-weight-bold">
                                <i class="fas fa-angle-right"></i> {{ $factory_option->option_name }}
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-2">
                        <p>Dealer Added Options</p>
                    </div>
                    <div class="col-md-4">
                        @foreach ($order->dealerOptions()->get() as $dealer_option)
                            <div class="font-weight-bold">
                                <i class="fas fa-angle-right"></i> {{ $dealer_option->option_name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @if (Helper::roleCheck(Auth::user()->id)->role != 'broker')
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Pricing</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        @include('partials.view-order-cost-section', ['name' => 'Basic Cost', 'value' => $order->list_price])
                        @include('partials.view-order-cost-section', ['name' => 'Metallic Paint', 'value' => $order->metallic_paint])
                        @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->basicSubTotal()])
                    </div>
                    <div class="col-6">
                        @include('partials.view-order-cost-section', ['name' => 'Dealer Discount @ ' . $order->dealer_discount . '%', 'value' => $order->basicDealerDiscount()])
                        @include('partials.view-order-cost-section', ['name' => 'Manufacturer Support @ ' . $order->manufacturer_discount . '%', 'value' => $order->basicManufacturerSupport()])
                        @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->basicDiscountedTotal()])
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col">
                                <h6 class="font-weight-bold">Factory Options</h6>
                                @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->factoryOptionsSubTotal()])
                                @include('partials.view-order-cost-section', ['name' => 'Discount', 'value' => $order->factoryOptionsDiscount()])
                                @include('partials.view-order-cost-section', ['name' => 'Total', 'value' => $order->factoryOptionsTotal()])
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="font-weight-bold">Dealer Fit Options</h6>
                        @include('partials.view-order-cost-section', ['name' => 'Total', 'value' => $order->dealerOptionsTotal()])
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        @include('partials.view-order-cost-section', ['name' => 'Manufacturer Delivery Cost', 'value' => $order->manufacturer_delivery_cost])
                        @include('partials.view-order-cost-section', ['name' => 'Onward Delivery', 'value' => $order->onward_delivery])
                        @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->invoiceSubTotal()])
                        @include('partials.view-order-cost-section', ['name' => 'VAT @ 20%', 'value' => $order->invoiceVat()])

                    </div>
                    <div class="col">
                        @include('partials.view-order-cost-section', ['name' => '1st Registration Fee', 'value' => $order->first_reg_fee])
                        @include('partials.view-order-cost-section', ['name' => 'Road Fund Licence', 'value' => $order->rfl_cost])

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @include('partials.view-order-cost-section', ['name' => 'Total', 'value' => $order->invoiceTotal()])
                    </div>
                    <div class="col">
                        @include('partials.view-order-cost-section', ['name' => 'Invoice Value', 'value' => $order->invoiceValue()])
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        @include('partials.view-order-cost-section', ['name' => 'Please invoice funder for', 'value' => $order->invoice_funder_for])
                        <h6 class="font-weight-bold">We will invoice you for the difference</h6>
                        @include('partials.view-order-cost-section', ['name' => 'inc VAT', 'value' => $order->invoiceDifferenceIncVat()])
                        @include('partials.view-order-cost-section', ['name' => 'exc VAT', 'value' => $order->invoiceDifferenceExVat()])
                    </div>
                </div>
            </div>
            @endif
            @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Admin Information</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <p>Customer Name</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->customer_name }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Company Name</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->company_name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p>Dealership</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ Helper::getCompanyName($order->dealership) }}</p>
                    </div>
                    <div class="col-md-2">
                        <p>Broker</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ Helper::getCompanyName($order->broker) }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p>Customer Phone Number</p>
                    </div>
                    <div class="col-md-4">
                        <p class="font-weight-bold">{{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>
            @endif
            <!-- Card Footer -->
            <div class="card-footer text-right">
                <a href="{{ route('pipeline') }}" class="btn btn-secondary">Back</a>
                @if (Helper::roleCheck(Auth::user()->id)->role != 'broker')
                    <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-secondary"
                       download>Download Order PDF</a>
                    <a href="{{ route('order.edit', $order->id) }}" class="btn btn-warning">Edit Vehicle</a>
                @elseif (Helper::roleCheck(Auth::user()->id)->role == 'broker')
                    @if ($order->vehicle_status == 1)
                    <a href="{{ route('order.reserve', $order->id) }}" class="btn btn-primary">Reserve Vehicle</a>
                    @elseif (is_null($order->customer_name) && is_null($order->company_name))
                    <a href="{{ route('order.reserve', $order->id) }}" class="btn btn-warning">Edit Reservation Details</a>
                    @endif
                @endif
                @if ($order->vehicle_status == 3)
                    @if (Helper::roleCheck(Auth::user()->id)->role == 'admin' && $order->admin_accepted == 0)
                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                    <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                    @elseif (Helper::roleCheck(Auth::user()->id)->role == 'dealer' && $order->admin_accepted == 1 && $order->dealer_accepted == 0)
                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                    <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                    @elseif (Helper::roleCheck(Auth::user()->id)->role == 'broker' && $order->admin_accepted == 1 && $order->broker_accepted == 0)
                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                    <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                    @endif
                @endif
            </div>
        </div>
    </div>

</div>

</div>
@endsection
