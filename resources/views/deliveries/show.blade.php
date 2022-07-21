@extends('layouts.main', [
    'title' => 'View Delivery',
    'activePage' => 'view-delivery'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">View Delivery Details - Order #{{ $delivery->order->id }}</h1>
                @include('partials.successMsg')
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Delivery Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p>Ford Order no.</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->order->order_ref ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Customer Name</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->order->customer->name() ?? '--' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Registration Number</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->order->vehicle->reg ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Chassis</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->order->vehicle->chassis ?? '--' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Funder</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->order->invoice_company->company_name ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Partner</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->order->broker->company_name ?? '--' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Delivery Date</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ \Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y') ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Delivery Address</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->delivery_address1 }}<br>{{ $delivery->delivery_address2 }}
                                    <br>{{ $delivery->delivery_town }}<br>{{ $delivery->delivery_city }}<br>{{ $delivery->delivery_postcode }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Contact Name</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->contact_name ?? '--' }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Contact Number</p>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">{{ $delivery->contact_number ?? '--' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Funder Confirmation</p>
                            </div>
                            <div class="col-md-4">
                                @if($delivery->funder_confirmation)
                                    <a href="{{ asset( '/storage/' . $delivery->funder_confirmation) }}" class="btn btn-primary" download>Download</a>
                                @else
                                    <p>Funder Confirmation not uploaded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        @if($delivery->order->vehicle->vehicle_status === 5)
                            @can('admin')
                                <a href="{{ route('delivery.accept', $delivery->id) }}" class="btn btn-primary">Accept Delivery</a>
                            @endcan
                        @endif
                        <a href="{{ route('delivery.edit', $delivery->id) }}" class="btn btn-primary">Amend Delivery</a>
                        <a href="{{ route('delivery.cancel', $delivery->id) }}" class="btn btn-danger">Cancel Delivery</a>
                    </div>
                </div>
                @livewire('comment-box', ['commentable_id' => $delivery->id, 'commentable_type' => 'delivery'])
            </div>
        </div>
@endsection
