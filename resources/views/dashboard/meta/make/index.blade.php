@extends('layouts.main', [
    'title' => 'Manage Make Options',
    'activePage' => 'make-manager'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <h1 class="h3 mb-4 text-gray-800">Manage Make</h1>

            @include('partials.successMsg')

            <livewire:make-editor />

        </div>

    </div>



    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')

@endpush
