@extends('layouts.main', [
    'title' => 'Reserve Vehicle - #{{ $order_details->id }}',
    'activePage' => 'edit-order'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Content Row -->
<div class="row justify-content-center">

  <!-- Doughnut Chart -->
  <div class="col-lg-10">
    <h1 class="h3 mb-4 text-gray-800">Reserve Vehicle - #{{ $order_details->id }}</h1>
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
            <div class="row">
                <p>In order to reserve a vehicle, you must first provide either a customer name or a company name. You can proceed with the application without entering these details
                but please note that if you do not enter any information, you will have 48 hours to provide this information or this vehicle will be placed back into the 
                stock pipeline for others to reserve</p>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
                <div class="col-md-6">
                    <input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" placeholder="e.g. Ted Moseby" value="{{ old('customer_name') }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="company_name">Company Name</label>
                <div class="col-md-6">
                    <input type="text" name="company_name" id="company_name" class="form-control" autocomplete="off" placeholder="e.g. Mosbius Designs" value="{{ old('company_name') }}"/>
                </div>
            </div>
        </div>
        <!-- Card Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('pipeline') }}" class="btn btn-secondary">Cancel</a>
            <button type="button" class="btn btn-primary" id="complete-form">Complete Reservation</button>
        </div>
      </div>

      <div class="modal fade" id="validateEmpty" tabindex="-1" role="dialog" aria-labelledby="validateEmptyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validateEmptyLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                You have not entered any customer information!<br><br>
                You will have 48 hours from now to enter the customer information or the vehicle will be placed back into Leden Stock and Pipeline for others to reserve.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Continue</button>
            </div>
            </div>
        </div>
      </div>

    </form>
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection

@push('custom-scripts')
<script>
$('#complete-form').on('click', function(){
    if ($('#customer_name').val() == '' && $('#company_name').val() == '') {
        $('#validateEmpty').modal({
            keyboard: true,
            show: true,
        });
    } else {
        $('#reserve-form').submit();
    }
});
</script>
@endpush