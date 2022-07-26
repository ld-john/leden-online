@extends('layouts.main', [
    'title' => 'Manage Type Options',
    'activePage' => 'type-manager'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Manage Type Options</h1>

                @include('partials.successMsg')

                <livewire:meta-editor :metatype="'type'" />

            </div>

        </div>

    </div>


    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')

@endpush
