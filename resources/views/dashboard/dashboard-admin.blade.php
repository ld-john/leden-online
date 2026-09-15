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

            <!-- Doughnut Chart -->
            <div class="col-lg-6">
                <div class="card shadow mb-4 h-300">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between h-75p">
                        <h6 class="m-0 font-weight-bold text-l-blue">Status Report</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row g-1 row-cols-4">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Download</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($statuses as $status)
                                    <tr>
                                        <th>{{ $status->label() }}</th>
                                        <th>{{ $vehicle_statuses[$status->label()] ?? 0 }}</th>
                                        <th>
                                            <a href="{{ route('export.status_export', $status->slug()) }}"
                                               class="btn btn-primary btn-sm">
                                                {{ $status->label() }}
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifications -->
            @include('dashboard.partials.notifications')
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Messages -->
            <div class="col-lg-12">
                @include('dashboard.partials.messages')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection

