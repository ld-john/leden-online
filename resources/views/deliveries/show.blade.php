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
                            <x-data-field label="Ford Order no" :value="$delivery->order->order_ref" />
                            <x-data-field label="Customer Name" :value="$delivery->order->customer->name()" />
                        </div>
                        <div class="row">
                            <x-data-field label="Registration Number" :value="$delivery->order->vehicle->reg" />
                            <x-data-field label="Chassis" :value="$delivery->order->vehicle->chassis" />
                        </div>
                        <div class="row">
                            <x-data-field label="Funder" :value="$delivery->order->invoice_company->company_name" />
                            <x-data-field label="Partner" :value="$delivery->order->broker->company_name" />
                        </div>
                        <div class="row">
                            <x-data-field label="Delivery Date" :value="\Carbon\Carbon::parse($delivery->delivery_date)->format('d/m/Y')" />
                            <x-data-field label="Delivery Address">
                                {{ $delivery->delivery_address1 }}<br>
                                @if($delivery->delivery_address2)
                                    {{ $delivery->delivery_address2 }}<br>
                                @endif
                                @if($delivery->delivery_town)
                                    {{ $delivery->delivery_town }}<br>
                                @endif
                                @if($delivery->delivery_city)
                                    {{ $delivery->delivery_city }}<br>
                                @endif
                                {{ $delivery->delivery_postcode }}
                            </x-data-field>
                        </div>
                        <div class="row">
                            <x-data-field label="Contact Name" :value="$delivery->contact_name" />
                            <x-data-field label="Contact Number" :value="$delivery->contact_number" />
                        </div>
                        <div class="row">
                            <x-data-field label="Funder Confirmation">
                                @if($delivery->funder_confirmation)
                                    @if(Storage::exists($delivery->funder_confirmation))
                                        <a href="{{ asset(\Illuminate\Support\Facades\Storage::url($delivery->funder_confirmation)) }}" class="btn btn-primary" download>Download</a>
                                    @endif
                                @else
                                    Funder Confirmation is not uploaded, or something went wrong
                                @endif
                            </x-data-field>
                            <x-data-field label="Delivery Agent" :value="$delivery->deliveryAgent?->company_name" />
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
