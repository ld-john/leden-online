@extends('layouts.main', [
    'title' => 'CSV Upload - Complete',
    'activePage' => 'upload.csv-upload'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

  <!-- Doughnut Chart -->
  <div class="col-lg-10">
    <h1 class="h3 mb-4 text-gray-800">Completed CSV Uploads</h1>
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
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">Uploaded Orders</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <p>
                        The list below contains all the orders you have just imported. You will be able to edit each order here. Clicking the edit/view buttons will
                        open up the order in a new tab so you can refer back to this list and edit multiple orders.
                    </p>
                </div>
            </div>
            @foreach ($all_orders as $order)
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>#{{ $order['order_id'] }} - {{ $order['vehicle_make'] }} {{ $order['vehicle_model'] }}</strong>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('order.edit', $order['order_id']) }}" class="btn btn-warning" target="_blank"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('order.show', $order['order_id']) }}" class="btn btn-primary" target="_blank"><i class="far fa-eye"></i> View</a>
                </div>
            </div>
            @endforeach
        </div>
      </div>
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection
