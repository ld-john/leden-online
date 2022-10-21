@extends('layouts.main', [
    'title' => 'Manage Initial Payments',
    'activePage' => 'initial-payment-editor'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Manage Initial Payments</h1>
                <livewire:finance.initial-payments-editor />
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
