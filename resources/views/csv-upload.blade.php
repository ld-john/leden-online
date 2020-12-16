@extends('layouts.main', [
    'title' => 'CSV Upload',
    'activePage' => 'csv-upload'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

  <!-- Doughnut Chart -->
  <div class="col-lg-10">
    <h1 class="h3 mb-4 text-gray-800">Import Orders</h1>
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
    <form method="POST" action="{{ route('csv_upload.import') }}" enctype="multipart/form-data">
      @csrf
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">Import CSV</h6>
            <a href="user-uploads/csv-uploads/csv_order_upload_template.csv" type="download" class="btn btn-sm btn-info"><i class="fas fa-file-download"></i> Download CSV Template</a>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-12">
                    <p>
                        Use the below form to upload orders in bulk. Once orders have been added to the system, you will be given an oppurtunity to edit these orders 
                        straight away from a seperate page.
                    </p>
                    <p>
                        You can download a template file using the button above.
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="upload_type">Type of upload</label>
                <div class="col-md-6">
                    <select name="upload_type" required class="form-control">
                        <option value="">Select an upload type</option>
                        <option value="create">Create Orders</option>
                        <option value="delete">Delete Orders</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="file">Upload Document<br>
                    <small>Allowed file types - CSV<br>Max file size - 1MB</small>
                </label>
                <div class="col-md-6">
                    <input type="file" required accept=".csv" name="file" id="file" />
                    @error('file')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="show_in_ford_pipeline">Show in Ford Pipleine?</label>
                <div class="col-md-6">
                    <select name="show_in_ford_pipeline" required class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Card Footer -->
        <div class="card-footer text-right">
            <button class="btn btn-primary" type="submit">Upload CSV</button>
        </div>
      </div>
    </form>
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection