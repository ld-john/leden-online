@extends('layouts.main', [
    'title' => 'Dashboard',
    'activePage' => 'dashboard'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">

        <!-- Content Row -->
        <div class="row">
            @include('dashboard.partials.boxes')
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Notifications -->
            @include('dashboard.partials.notifications')

            <!-- Messages -->
            <div class="col-lg-6">
                @include('dashboard.partials.messages')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')

@endpush
