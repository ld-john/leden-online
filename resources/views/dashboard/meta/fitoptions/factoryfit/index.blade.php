@extends('layouts.main', [
    'title' => 'Manage Factory Fit Options',
    'activePage' => 'factory-fit-options'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Manage Factory Fit Options</h1>

                @include('partials.successMsg')

                @livewire('fit-options.fit-options-editor', ['fitType' => 'factory'])

            </div>

        </div>

    </div>


    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')

@endpush
