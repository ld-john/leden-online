@extends('layouts.main', [
    'title' => $title,
    'activePage' => $activePage
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Create Order</h1>
                @livewire('order-form')
            </div>

        </div>
        <!-- /.container-fluid -->

        @endsection

        @push('custom-scripts')
            <script>
            </script>
    @endpush
