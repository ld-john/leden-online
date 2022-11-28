@extends('layouts.main', [
    'title' => $title,
    'activePage' => $active_page
    ])

@section('content')
    <!-- Begin Page Content -->

    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">{{$title}}</h1>
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <h2 class="h4">Vehicles</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Derivative</th>
                                    <th>Engine</th>
                                    <th>Colour</th>
                                    <th>Type</th>
                                    <th>Chassis</th>
                                    <th>Registration</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $vehicles as $vehicle )
                                    <tr>
                                        <td>
                                            <strong>{{ $vehicle->id ?? '' }}</strong>
                                            @if($vehicle->orbit_number)
                                                <br>(Orbit Number: {{ $vehicle->orbit_number }})
                                            @endif
                                        </td>
                                        <td>{{ $vehicle->manufacturer->name ?? '' }}</td>
                                        <td>{{ $vehicle->model ?? '' }}</td>
                                        <td>{{ $vehicle->derivative ?? '' }}</td>
                                        <td>{{ $vehicle->engine ?? '' }}</td>
                                        <td>{{ $vehicle->colour ?? '' }}</td>
                                        <td>{{ $vehicle->type ?? '' }}</td>
                                        <td>{{ $vehicle->chassis ?? '' }}</td>
                                        <td>{{ $vehicle->reg ?? '' }}</td>
                                        <td>{{ $vehicle->status() }}</td>
                                        <td>
                                            <div class="d-grid grid-cols-2 gap-2">
                                            <a
                                                    href="{{ route('vehicle.restore', $vehicle->id) }}"
                                                    class="btn btn-success"
                                                    data-toggle="tooltip"
                                                    title="Restore"
                                            >
                                                <i class="fa-solid fa-trash-arrow-up"></i>
                                            </a>
                                            <a
                                                    href="{{ route('vehicle.force-delete', $vehicle->id) }}"
                                                    class="btn btn-danger"
                                                    data-toggle="tooltip"
                                                    title="PERMANENTLY DELETE"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $vehicles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
