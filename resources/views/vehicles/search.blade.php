@extends('layouts.main', [
    'title' => 'Search Vehicle',
    'activePage' => 'search-vehicle'
    ])

@section('content')
    <div class="container-fluid">

        <!-- Content Row -->

            <h1 class="h3 mb-4 text-gray-800">Leden Universal Search</h1>
            <div class="card shadow mb-4">
                @livewire('vehicle.universal-search')
            </div>
        </div>
@endsection
