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

            <!-- Vehicle Offers -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Special Offers</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="blue-background text-white">
                                    <th>Leden ID</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Engine</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $data as $row )
                                    <tr>
                                        <td>{{ $row->id ?? '' }}</td>
                                        <td>{{ $row->manufacturer->name ?? '' }}</td>
                                        <td>{{ $row->model ?? '' }}</td>
                                        <td>{{ $row->engine ?? '' }}</td>
                                        <td>{{ $row->type ?? '' }}</td>
                                        <td>
                                            <a href="{{route('vehicle.show', [$row->id])}}" class="btn btn-primary"><i class="far fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Orders in Stock -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">In Stock Orders</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        @livewire('dashboard.broker.in-stock-orders-table')
                    </div>
                </div>
            </div>

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
