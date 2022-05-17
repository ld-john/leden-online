@extends('layouts.main', [
    'title' => 'Order Vehicle - #{{ vehicle->id }}',
    'activePage' => 'edit-order'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Order Vehicle - #{{ $vehicle->id }}</h1>
                @if (isset($order))
                    <p>Vehicle is already on order</p>
                @else
                    @livewire('order.order-form', ['vehicle' => $vehicle])
                @endif
            </div>
        </div>
    </div>
@endsection
