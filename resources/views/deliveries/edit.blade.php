@extends('layouts.main', [
    'title' => 'Amend Delivery',
    'activePage' => 'amend-delivery'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Amend Delivery</h1>
                @livewire('delivery.delivery-form', ['order' => $delivery->order])
            </div>

        </div>
        <!-- /.container-fluid -->

        @endsection

        @push('custom-scripts')
            <script>
            </script>
    @endpush
