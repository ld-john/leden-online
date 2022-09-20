@extends('layouts.main', [
    'title' => 'Create Vehicle',
    'activePage' => 'create-vehicle'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">Create New Vehicle</h1>
            @livewire('vehicle.vehicle-form')
        </div>
    </div>
@endsection
