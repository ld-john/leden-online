@extends('layouts.main', [
    'title' => 'Company Management',
    'activePage' => 'company-manager'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">Company Management</h1>
        @include('partials.successMsg')
        <div class="card shadow mb-4">
            <div class="card-body">
                @livewire('customer.company-table')
            </div>
        </div>

    </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection
