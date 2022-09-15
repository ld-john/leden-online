@extends('layouts.main', [
    'title' => 'Manage News and Promos',
    'activePage' => 'manage-updates'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('partials.successMsg')
                <h1 class="h3 mb-4 text-gray-800">Manage News and Promos</h1>
                @livewire('update.manage-updates')
            </div>
        </div>
    </div>
@endsection
