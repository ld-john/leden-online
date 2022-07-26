@extends('layouts.main', [
    'title' => 'Manage Trim Options',
    'activePage' => 'trim-manager'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Manage Trim Options</h1>

                @include('partials.successMsg')

                <livewire:meta-editor :metatype="'trim'" />
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

