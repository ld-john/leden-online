@extends('layouts.main', [
    'title' => 'Manage Deliveries',
    'activePage' => 'deliveries'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Manage Deliveries</h1>

                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        @livewire('order-table', ['status' => $status, 'view' => 'delivery'])
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
