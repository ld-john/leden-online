@extends('layouts.main', [
    'title' => 'Search Vehicle',
    'activePage' => 'search-vehicle'
    ])

@section('content')
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Search Vehicles</h1>
                <div class="card shadow mb-4">
                    @livewire('vehicle.universal-search')
                </div>
            </div>
        </div>
    </div>
@endsection
