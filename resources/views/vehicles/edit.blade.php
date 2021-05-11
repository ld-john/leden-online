@extends('layouts.main', [
    'title' => 'Edit Vehicle',
    'activePage' => 'edit-vehicle'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Edit Vehicle</h1>
                @livewire('vehicle-form', ['vehicle' => $vehicle])
            </div>
        </div>
    </div>
@endsection
