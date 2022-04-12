@extends('layouts.main', [
    'title' => 'User Management',
    'activePage' => 'user-manager'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">User Management</h1>
        @include('partials.successMsg')
        <div class="card shadow mb-4">
            <div class="card-body">
                @livewire('user-table')
            </div>
        </div>

    </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection
