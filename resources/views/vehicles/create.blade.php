@extends('layouts.main', [
    'title' => 'Create Vehicle',
    'activePage' => 'create-vehicle'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Create New Vehicle</h1>
                @livewire('vehicle-form')
            </div>
        </div>
    </div>
@endsection
