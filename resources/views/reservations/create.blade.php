@extends('layouts.main', [
    'title' => 'Reserve Vehicle',
    'activePage' => 'reserve-vehicle'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Reserve Vehicle</h1>
                @livewire('reservations.admin-override', ['vehicle' => $vehicle])
            </div>
        </div>
    </div>
@endsection
