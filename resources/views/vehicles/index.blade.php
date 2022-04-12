@extends('layouts.main', [
    'title' => $title,
    'activePage' => $active_page
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">{{$title}}</h1>
                @include('partials.successMsg')

                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        @livewire('vehicle-table', ['ringfenced' => $ringfenced, 'fordpipeline' => $fordpipeline])
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
