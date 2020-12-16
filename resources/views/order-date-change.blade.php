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
    <h1 class="h3 mb-4 text-gray-800">Change Delivery Date - #{{ $order_details->id }}</h1>
    @if (!empty($successMsg))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ $successMsg }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @elseif (!empty($errorMsg))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ $errorMsg }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif
    <form method="POST" action="{{ route('order.reserve.add', $order_details->id) }}" id="reserve-form" enctype="multipart/form-data">
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
                    <p class="font-weight-bold">{{ $order_details->vehicle_make }}</p>
                </div>
                <div class="col-md-2">
                    <p>Vehicle Model</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_model }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Vehicle Type</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_type }}</p>
                </div>
                <div class="col-md-2">
                    <p>Vehicle Derivative</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_derivative }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Vehicle Engine</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_engine }}</p>
                </div>
                <div class="col-md-2">
                    <p>Vehicle Transmission</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_trans }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Vehicle Doors</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_doors }}</p>
                </div>
                <div class="col-md-2">
                    <p>Vehicle Colour</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_colour }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Vehicle Body</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_body }}</p>
                </div>
                <div class="col-md-2">
                    <p>Vehicle Trim</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->vehicle_trim }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Chassis Prefix</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->chassis_prefix }}</p>
                </div>
                <div class="col-md-2">
                    <p>Chassis</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->chassis }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Model Year</p>
                </div>
                <div class="col-md-4">
                    <p class="font-weight-bold">{{ $order_details->model_year }}</p>
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
                    <input type="text" name="delivery_date" id="delivery_date" required class="form-control" autocomplete="off" placeholder="e.g. 20/06/2020" value="{{ old('delivery_date') }}"/>
                </div>
            </div>
        </div>
        <!-- Card Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('order.show', $order_details->id) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" id="complete-form">Change Delivery Date</button>
        </div>
      </div>

    </form>
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection
