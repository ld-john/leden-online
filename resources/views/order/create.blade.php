@extends('layouts.main', [
    'title' => 'Create Order',
    'activePage' => 'create-order'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <div class="row">
            <!-- Doughnut Chart -->
            <h1 class="h3 mb-4 text-gray-800">Create Order</h1>
            @livewire('order.order-form')


        </div>
        <!-- /.container-fluid -->
    </div>

@endsection
