@extends('layouts.main', [
    'title' => 'Change Delivery Date - #{{ $order_details->id }}',
    'activePage' => 'order-date-change'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Change Delivery Date - #{{ $order->id }}</h1>
                @include('partials.successMsg')
                <form method="POST" action="{{ route('order.date.update', $order->id) }}" id="date-change-form" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Vehicle Details</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Vehicle Make</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->manufacturer->name }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Vehicle Model</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->model }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <p>Vehicle Type</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->type }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Vehicle Derivative</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->derivative }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <p>Vehicle Engine</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->engine }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Vehicle Transmission</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->transmission }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <p>Vehicle Doors</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->doors }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Vehicle Colour</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->colour }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Vehicle Body</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->body }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Vehicle Trim</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->trim }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Chassis Prefix</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->chassis_prefix }}</p>
                                </div>
                                <div class="col-md-2">
                                    <p>Chassis</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->chassis }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Model Year</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-weight-bold">{{ $order->vehicle->model_year }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Required Details</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="delivery_date">Request Delivery Date</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_date" id="delivery_date" class="form-control" autocomplete="off" placeholder="e.g. 20/06/2020" value="{{ old('delivery_date') }}"/>
                                </div>
                            </div>
                        </div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Card Footer -->
                        <div class="card-footer text-right">
                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="complete-form">Change Delivery Date</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
