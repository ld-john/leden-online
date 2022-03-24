@extends('layouts.main', [
    'title' => 'Edit Order',
    'activePage' => 'edit-order'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Content Row -->
<div class="row justify-content-center">

  <!-- Doughnut Chart -->
  <div class="col-lg-10">
    <h1 class="h3 mb-4 text-gray-800">Edit Order - #{{ $order_details->id }}</h1>
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
    <form method="POST" action="{{ route('order.update', $order_details->id) }}" id="reserve-form" enctype="multipart/form-data">
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
            <h6 class="m-0 font-weight-bold text-l-blue">Edit Information</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="chassis_prefix">Chassis Prefix</label>
                <div class="col-md-6">
                    <input type="text" required name="chassis_prefix" id="chassis_prefix" class="form-control" autocomplete="off" placeholder="e.g. WF0E" value="{{ old('chassis_prefix') ?? $order_details->chassis_prefix }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="chassis">Chassis</label>
                <div class="col-md-6">
                    <input type="text" required name="chassis" id="chassis" class="form-control" autocomplete="off" placeholder="e.g. WF0EXXTTGEJG05509" value="{{ old('chassis') ?? $order_details->chassis }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="vehicle_reg">Registration Number</label>
                <div class="col-md-6">
                    <input type="text" required name="vehicle_reg" id="vehicle_reg" class="form-control" autocomplete="off" placeholder="e.g. e.g WM63 NKZ" value="{{ old('vehicle_reg') ?? $order_details->vehicle_reg }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="vehicle_registered_on">Vehicle Registered</label>
                <div class="col-md-6">
                    <input type="text" name="vehicle_registered_on" id="vehicle_registered_on" class="form-control" autocomplete="off" placeholder="e.g. 14/05/2020" value="@if (is_null($order_details->vehicle_registered_on)){{ old('vehicle_registered_on') }}@else{{ old('vehicle_registered_on') ?? date('d/m/Y', strtotime($order_details->vehicle_registered_on)) }}@endif"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="vehicle_status">Vehicle Status</label>
                <div class="col-md-6">
                    <select class="form-control" required name="vehicle_status" id="vehicle_status">
                        <option value="1" @if ($order_details->vehicle_status == 1) selected @endif>In Stock</option>
                        <option value="3" @if ($order_details->vehicle_status == 3) selected @endif>Ready for Delivery</option>
                        <option value="4" @if ($order_details->vehicle_status == 4) selected @endif>Factory Order</option>
                        <option value="6" @if ($order_details->vehicle_status == 6) selected @endif>Delivery Booked</option>
                        <option value="7" @if ($order_details->vehicle_status == 7) selected @endif>Completed Orders</option>
                        <option value="10" @if ($order_details->vehicle_status == 10) selected @endif>Europe VHC</option>
                        <option value="11" @if ($order_details->vehicle_status == 11) selected @endif>UK VHC</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Card Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('pipeline') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Edit Order</button>
        </div>
      </div>

    </form>
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection
