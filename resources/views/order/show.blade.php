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
                @include('partials.successMsg')

                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Details</h6>
                        <div class="d-flex align-items-center">
                            <strong>Vehicle Status:</strong> <span class="badge bg-primary ms-3"> {{ $order->vehicle->status() }}</span>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Make</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->manufacturer->name ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Model</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->model ?? '--' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Orbit Number</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->orbit_number ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Ford Order Ref</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->ford_order_number ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Type</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->type ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Derivative</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->derivative ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Engine</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->engine ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Transmission</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->transmission ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Vehicle Reg</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->reg ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Vehicle Colour</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->colour ?? '--'}}</p>
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
                                <p class="font-weight-bold">{{ $order->vehicle->trim ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <p>Chassis Prefix</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->chassis_prefix ?? '--'}}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Chassis</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->vehicle->chassis ?? '--'}}</p>
                            </div>
                        </div>

                        <div class="row">
                            @if (Auth::user()->role != 'broker')
                                <div class="col-md-2">
                                    <p>Model Year</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->model_year ?? '--'}}</p>
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
                                    @if( isset ($order->delivery_date) && ( $order->delivery_date != '0000-00-00 00:00:00') )
                                        {{ date('d/m/Y', strtotime($order->delivery_date)) }}
                                    @else
                                        TBC
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <div class="col-md-2">
                                <p>Planned Build Date</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">
                                    @if( isset ($order->vehicle->build_date) && ( $order->vehicle->build_date != '0000-00-00 00:00:00') )
                                        {{ date('d/m/Y', strtotime($order->vehicle->build_date)) }}
                                    @else
                                        TBC
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Registration Company</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->registration_company?->company_name }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Invoice Company</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $order->invoice_company?->company_name }}</p>
                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->role != 'broker')
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Pricing</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    @include('partials.view-order-cost-section', ['name' => 'Basic Cost', 'value' => $order->vehicle->list_price])
                                    @include('partials.view-order-cost-section', ['name' => 'Metallic Paint', 'value' => $order->vehicle->metallic_paint])
                                    @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->basicSubTotal()])
                                </div>
                                <div class="col-6">
                                    @include('partials.view-order-cost-section', ['name' => 'Dealer Discount @ ' . $order->invoice->dealer_discount . '%', 'value' => $order->basicDealerDiscount()])
                                    @include('partials.view-order-cost-section', ['name' => 'Manufacturer Support @ ' . $order->invoice->manufacturer_discount . '%', 'value' => $order->basicManufacturerSupport()])
                                    @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->basicDiscountedTotal()])
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="font-weight-bold">Factory Options</h6>
                                            <ul>
                                                @forelse($order->vehicle->factoryFitOptions() as $option)
                                                    @include('partials.view-order-cost-section', ['name' => $option->factoryOptionName, 'value' => $option->option_price])
                                                @empty
                                                    <li>No options selected</li>
                                                @endforelse
                                            </ul>
                                            @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->factoryOptionsSubTotal()])
                                            @include('partials.view-order-cost-section', ['name' => 'Discount', 'value' => $order->factoryOptionsDiscount()])
                                            @include('partials.view-order-cost-section', ['name' => 'Total', 'value' => $order->factoryOptionsTotal()])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="font-weight-bold">Dealer Fit Options</h6>
                                    <ul>
                                        @forelse($order->vehicle->dealerFitOptions() as $option)
                                            @include('partials.view-order-cost-section', ['name' => $option->dealerOptionName, 'value' => $option->option_price])
                                        @empty
                                            <li>No options selected</li>
                                        @endforelse
                                    </ul>
                                    @include('partials.view-order-cost-section', ['name' => 'Total', 'value' => $order->dealerOptionsTotal()])
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    @include('partials.view-order-cost-section', ['name' => 'Manufacturer Delivery Cost', 'value' => $order->invoice->manufacturer_delivery_cost])
                                    @include('partials.view-order-cost-section', ['name' => 'Onward Delivery', 'value' => $order->invoice->onward_delivery])
                                    @include('partials.view-order-cost-section', ['name' => 'Sub Total', 'value' => $order->invoiceSubTotal()])
                                    @include('partials.view-order-cost-section', ['name' => 'VAT @ 20%', 'value' => $order->invoiceVat()])

                                </div>
                                <div class="col">
                                    @include('partials.view-order-cost-section', ['name' => '1st Registration Fee', 'value' => $order->vehicle->first_reg_fee])
                                    @include('partials.view-order-cost-section', ['name' => 'Road Fund Licence', 'value' => $order->vehicle->rfl_cost])

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
                                    @include('partials.view-order-cost-section', ['name' => 'Please invoice funder for', 'value' => $order->invoice->invoice_funder_for])
                                    @if ($order->invoice->invoice_value_to_dealer)
                                        <h6 class="font-weight-bold">We will invoice you for the difference</h6>
                                        @include('partials.view-order-cost-section', ['name' => 'inc VAT', 'value' => $order->invoice->inc_vat_value()])
                                        @include('partials.view-order-cost-section', ['name' => 'exc VAT', 'value' => $order->invoice->invoice_value_to_dealer])
                                    @else
                                        <h6 class="font-weight-bold">Invoice from Dealer</h6>
                                        @include('partials.view-order-cost-section', ['name' => 'Dealer Invoice Value', 'value' => ($order->invoice->invoice_value_from_dealer)])
                                        @if(isset ($order->invoice->dealer_pay_date) && ( $order->invoice->dealer_pay_date != '0000-00-00 00:00:00'))
                                            @include('partials.view-order-date-section', ['name' => 'Dealer Invoice Pay Date', 'value' => $order->invoice->dealer_pay_date])
                                        @else
                                            <div class="row">
                                                <div class="col">
                                                    <p>Dealer Invoice Pay Date</p>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <p class="font-weight-bold">TBC</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($order->invoice->dealer_invoice_number)
                                            <div class="row">
                                                <div class="col">
                                                    <p>Dealer Invoice Number</p>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <p class="font-weight-bold">
                                                        {{$order->invoice->dealer_invoice_number}}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif



                    @if (Auth::user()->role == 'admin')
                        <!-- Card Header - Finance Information -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Finance Information</h6>
                    </div>
                    <!-- Card Body - Finance Information  -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                Finance Type
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $finance_type ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                Maintenance
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $maintenance ?? '--'}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                Term
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $term ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                Initial Payment
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $initial_payment ?? '--'}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                Terminal Pause
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">
                                    @if($order->terminal_pause)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-2">
                                Mileage
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $mileage ?? '--'}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                @include('partials.view-order-cost-section', ['name' => 'Rental', 'value' => ($order->rental)])
                            </div>
                            <div class="col-md-6">
                                @include('partials.view-order-cost-section', ['name' => 'Maintenance Rental', 'value' => ($order->maintenance_rental)])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                Renewal Date
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">
                                    @if( isset ($order->renewal_date) && ( $order->renewal_date != '0000-00-00 00:00:00') )
                                        {{ date('d/m/Y', strtotime($order->renewal_date)) }}
                                    @else
                                        --
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                {{-- This space left intentionally blank --}}
                            </div>
                        </div>
                    </div>
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
                                    <p class="font-weight-bold">{{ $order->customer->customer_name ?? '--'}}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Company Name</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->customer->company_name ?? '--'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Dealership</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->dealer->company_name ?? '--'}}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Broker</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->broker->company_name ?? '--'}} @if($order->finance_broker) | {{ $order->finance_broker->company_name }} @endif</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Customer Phone Number</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->customer->phone_number ?? '--'}}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Broker Order Ref</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->broker_ref ?? '--'}}</p>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Card Footer -->
                    <div class="card-footer text-right">
                        <a href="{{ route('pipeline') }}" class="btn btn-secondary">Back</a>
                        @if (Auth::user()->role != 'broker')
                            <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-secondary">Download Order PDF</a>
                            @if( Auth::user()->role == 'admin')
                                <a href="{{ route('order.edit', $order->id) }}" class="btn btn-warning">Edit Order</a>
                            @endif
                        @endif
                        @if ($order->vehicle->vehicle_status == 3)
                            @if (Auth::user()->role == 'admin' && $order->admin_accepted == 0)
                                @if($order->delivery_date)
                                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                                @endif
{{--                                <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>--}}
                            @elseif (Auth::user()->role == 'dealer' && $order->admin_accepted == 1 && $order->dealer_accepted == 0)
                                @if($order->delivery_date)
                                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                                @endif
                                <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                            @elseif (Auth::user()->role == 'broker' && $order->admin_accepted == 1 && $order->broker_accepted == 0)
                                @if($order->delivery_date)
                                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                                @endif
                                <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                            @endif
                        @endif
                    </div>
                </div>
                @livewire('comment-box', ['commentable_id' => $order->id, 'commentable_type' => 'order'])
                @livewire('show-comment-box', ['commentable_id' => $order->vehicle->id, 'commentable_type' => 'vehicle'])
                @if($order->delivery)
                    @livewire('show-comment-box', ['commentable_id' => $order->delivery->id, 'commentable_type' => 'delivery'])
                @endif
            </div>
        </div>
    </div>
@endsection
