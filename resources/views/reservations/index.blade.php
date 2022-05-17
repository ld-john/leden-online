@extends('layouts.main', [
    'title' => 'Reservations',
    'activePage' => 'reservations'
    ])

@section('content')
    <!-- Begin Page Content -->


    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Reservations</h1>
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        @livewire('reservations.reservations-table')
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
