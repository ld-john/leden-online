@extends('layouts.main', [
    'title' => 'Reserve Vehicle - #{{ $order_details->id }}',
    'activePage' => 'edit-order'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Reserve Vehicle - #{{ $vehicle->id }}</h1>
                @if (isset($order))
                    <p>Vehicle is already reserved</p>
                @else
                    @livewire('order-form', ['vehicle' => $vehicle])
                @endif
            </div>
        </div>
    </div>
@endsection
