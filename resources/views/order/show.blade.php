@extends('layouts.main', [
    'title' => "View Order - #{$order->id}",
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
                            <strong>Vehicle Status:</strong>
                            <span class="badge bg-primary ms-3">
                                {{ $order->vehicle->status?->label() }}
                                @if($order->vehicle->vehicle_status->isInStock() && $order->vehicle->in_stock_at)
                                    ({{ $order->vehicle->time_in_stock }})
                                @endif
                            </span>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <x-detail-pair label="Vehicle Make" :value="$order->vehicle->manufacturer?->name" />
                            <x-detail-pair label="Vehicle Model" :value="$order->vehicle->model" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Orbit Number" :value="$order->vehicle->orbit_number" />
                            <x-detail-pair label="Ford Order Ref" :value="$order->vehicle->ford_order_number" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Vehicle Type" :value="$order->vehicle->type" />
                            <x-detail-pair label="Vehicle Derivative" :value="$order->vehicle->derivative" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Vehicle Engine" :value="$order->vehicle->engine" />
                            <x-detail-pair label="Vehicle Transmission" :value="$order->vehicle->transmission" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Vehicle Reg" :value="$order->vehicle->reg" />
                            <x-detail-pair label="Vehicle Colour" :value="$order->vehicle->colour" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <x-detail-pair label="Vehicle Trim" :value="$order->vehicle->trim" />
                        </div>

                        <div class="row">
                            <x-detail-pair label="Chassis Prefix" :value="$order->vehicle->chassis_prefix" />
                            <x-detail-pair label="Chassis" :value="$order->vehicle->chassis" />
                        </div>

                        <div class="row">
                            @cannot('broker')
                                <x-detail-pair label="Model Year" :value="$order->vehicle->model_year" />
                            @else
                                <div class="col-md-6 d-none d-md-block"></div>
                            @endcannot

                            @php
                                $isFullyAccepted = $order->admin_accepted && $order->dealer_accepted && $order->broker_accepted;
                                $deliveryLabel = $isFullyAccepted ? 'Delivery Date' : 'Proposed Delivery Date';
                                $formattedDeliveryDate = $order->delivery_date?->format('d/m/Y') ?? 'TBC';
                                $formattedBuildDate = $order->vehicle->build_date?->format('d/m/Y') ?? 'TBC';
                            @endphp

                            <x-detail-pair :label="$deliveryLabel" :value="$formattedDeliveryDate" />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- This space left intentionally blank --->
                            </div>
                            <x-detail-pair label="Planned Build Date" :value="$formattedBuildDate" />
                        </div>
                        <div class="row">
                            <x-detail-pair label="Registration Company" :value="$order->registration_company?->company_name" />
                            <x-detail-pair label="Invoice Company" :value="$order->invoice_company?->company_name" />
                        </div>
                    </div>
                    @cannot('broker')
                        {{-- Vehicle Pricing Section --}}
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Pricing</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <x-view-order-cost-section name="Basic Cost" :value="$order->vehicle->list_price" />
                                    <x-view-order-cost-section name="Metallic Paint" :value="$order->vehicle->metallic_paint" />
                                    <x-view-order-cost-section name="Sub Total" :value="$order->basicSubTotal()" />
                                </div>
                                <div class="col-6">
                                    <x-view-order-cost-section :name="'Dealer Discount @ ' . ($order->invoice->dealer_discount ?? 0) . '%'" :value="$order->basicDealerDiscount()" />
                                    <x-view-order-cost-section :name="'Manufacturer Support @ ' . ($order->invoice->manufacturer_discount ?? 0) . '%'" :value="$order->basicManufacturerSupport()" />
                                    {{-- Tactical Offers Section --}}
                                    @foreach($order->vehicle?->tacticalOffers as $offer)
                                        <x-view-order-cost-section
                                                :name="'Tactical Offer: ' . $offer->name"
                                                :value="$offer->pivot->applied_amount"
                                        />
                                    @endforeach

                                    @if($order->vehicle?->tacticalOffers->isNotEmpty())
                                        <x-view-order-cost-section
                                                name="Total Tactical Offers"
                                                :value="$order->vehicle?->tacticalOffers->sum('pivot.applied_amount')"
                                                class="text-success fw-bold"
                                        />
                                    @endif


                                    @can('admin')
                                        @if($order->invoice?->leden_discount)
                                            <x-view-order-cost-section name="Flat Discount" :value="$order->invoice->leden_discount" />
                                        @endif
                                    @endcan
                                    <x-view-order-cost-section name="Sub Total" :value="$order->basicDiscountedTotal()" />
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
                                                    <x-view-order-cost-section :name="$option->factoryOptionName" :value="$option->option_price" />
                                                @empty
                                                    <li>No options selected</li>
                                                @endforelse
                                            </ul>
                                            <x-view-order-cost-section name="Sub Total" :value="$order->factoryOptionsSubTotal()" />
                                            <x-view-order-cost-section name="Discount" :value="$order->factoryOptionsDiscount()" />
                                            <x-view-order-cost-section name="Total" :value="$order->factoryOptionsTotal()" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="font-weight-bold">Dealer Fit Options</h6>
                                    <ul>
                                        @forelse($order->vehicle->dealerFitOptions() as $option)
                                            <x-view-order-cost-section :name="$option->dealerOptionName" :value="$option->option_price" />
                                        @empty
                                            <li>No options selected</li>
                                        @endforelse
                                    </ul>
                                    <x-view-order-cost-section name="Total" :value="$order->dealerOptionsTotal()" />
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <x-view-order-cost-section name="Manufacturer Delivery Cost" :value="$order->invoice->manufacturer_delivery_cost" />
                                    <x-view-order-cost-section name="Onward Delivery" :value="$order->invoice->onward_delivery" />
                                    <x-view-order-cost-section name="Sub Total" :value="$order->invoiceSubTotal()" />
                                    <x-view-order-cost-section name="VAT @ 20%" :value="$order->invoiceVat()" />

                                </div>
                                <div class="col">
                                    <x-view-order-cost-section name="1st Registration Fee" :value="$order->vehicle->first_reg_fee" />
                                    <x-view-order-cost-section name="Road Fund Licence" :value="$order->vehicle->rfl_cost" />
                                    <hr>
                                    <x-view-order-cost-section name="Grant" :value="$order->invoice->grant" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-view-order-cost-section name="Total" :value="$order->invoiceTotal()" />
                                </div>
                                <div class="col">
                                    <x-view-order-cost-section name="Invoice Value" :value="$order->invoiceValue()" />
                                </div>
                            </div>
                            <hr>
                            {{-- Invoicing Breakdown --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <x-view-order-cost-section name="Please invoice funder for" :value="$order->invoice->invoice_funder_for" />

                                    @if ($order->invoice->invoice_value_to_dealer)
                                        <h6 class="fw-bold mt-3">We will invoice you for the difference</h6>
                                        <x-view-order-cost-section name="inc VAT" :value="$order->invoice->inc_vat_value()" />
                                        <x-view-order-cost-section name="exc VAT" :value="$order->invoice->invoice_value_to_dealer" />
                                    @else
                                        <h6 class="fw-bold mt-3">Invoice from Dealer</h6>
                                        <x-view-order-cost-section name="Dealer Invoice Value (exc. VAT)" :value="$order->invoice->invoice_value_from_dealer" />

                                        @php
                                            $dealerPayDate = $order->invoice->dealer_pay_date?->format('d/m/Y') ?? 'TBC';
                                        @endphp
                                            <x-detail-pair label="Dealer Invpoce Pay Date" :value="$dealerPayDate" />


                                        @if($order->invoice->dealer_invoice_number)
                                            <x-view-order-cost-section name="Dealer Invoice Number" :value="$order->invoice->dealer_invoice_number" />
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endcannot

                    @can('admin')
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Finance Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <x-detail-pair label="Finance Type" :value="$order->financeType?->option" />
                                <x-detail-pair label="Maintenance" :value="$order->maintenance?->option" />

                                <x-detail-pair label="Term" :value="$order->term?->option" />
                                <x-detail-pair label="Initial Payment" :value="$order->initialPayment?->option" />

                                <x-detail-pair label="Terminal Pause" :value="$order->terminal_pause ? 'Yes' : 'No'" />
                                <x-detail-pair label="Mileage" :value="$order->mileage?->option" />

                                <div class="col-md-6">
                                    <x-view-order-cost-section name="Rental" :value="$order->rental" />
                                </div>
                                <div class="col-md-6">
                                    <x-view-order-cost-section name="Maintenance Rental" :value="$order->maintenance_rental" />
                                </div>

                                @php
                                    $renewalDate = $order->renewal_date?->format('d/m/Y') ?? '--';
                                @endphp
                                <x-detail-pair label="Renewal Date" :value="$renewalDate" />
                            </div>
                        </div>

                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Admin Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <x-detail-pair label="Customer Name" :value="$order->customer?->customer_name" />
                                <x-detail-pair label="Company Name" :value="$order->customer?->company_name" />

                                <x-detail-pair label="Dealership" :value="$order->dealer?->company_name" />

                                @php
                                    $brokerValue = $order->broker?->company_name;
                                    if ($order->finance_broker) {
                                        $brokerValue .= ' | ' . $order->finance_broker->company_name;
                                    }
                                @endphp
                                <x-detail-pair label="Broker" :value="$brokerValue" />

                                <x-detail-pair label="Customer Phone Number" :value="$order->customer?->phone_number" />
                                <x-detail-pair label="Broker Order Ref" :value="$order->broker_ref" />
                            </div>
                        </div>
                    @endcan

                    {{-- Card Footer Actions --}}
                    <div class="card-footer text-end">
                        <a href="{{ route('pipeline') }}" class="btn btn-secondary">Back</a>

                        @cannot('broker')
                            <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-secondary">Download Order PDF</a>
                            @can('admin')
                                <a href="{{ route('order.edit', $order->id) }}" class="btn btn-warning">Edit Order</a>
                            @endcan
                        @endcannot

                        @if ($order->vehicle->vehicle_status == 3)
                            @can('admin')
                                @if(!$order->admin_accepted && $order->delivery_date)
                                    <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                                @endif
                            @endcan

                            @can('dealer')
                                @if($order->admin_accepted && !$order->dealer_accepted)
                                    @if($order->delivery_date)
                                        <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                                    @endif
                                    <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                                @endif
                            @endcan

                            @can('broker')
                                @if($order->admin_accepted && !$order->broker_accepted)
                                    @if($order->delivery_date)
                                        <a href="{{ route('order.date.accept', $order->id) }}" class="btn btn-success">Accept Delivery Date</a>
                                    @endif
                                    <a href="{{ route('order.date.change', $order->id) }}" class="btn btn-danger">Change Delivery Date</a>
                                @endif
                            @endcan
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
