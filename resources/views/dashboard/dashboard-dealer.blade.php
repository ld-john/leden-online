@extends('layouts.main', [
    'title' => 'Dashboard',
    'activePage' => 'dashboard'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row">
            @include('dashboard.partials.boxes')
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Notifications -->
            @include('dashboard.partials.notifications')

            <!-- Messages -->
            @include('dashboard.partials.messages')

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')

@endpush
