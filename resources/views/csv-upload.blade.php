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
    <h1 class="h3 mb-4 text-gray-800">Import Vehicles</h1>
    @include('partials.successMsg')
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
                        Use the below form to upload or delete vehicles in bulk. Once orders have been added to the system, you will be given an opportunity to edit these vehicles.
                    </p>
                    <p>
                        You can download a template file using the button above.
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="upload_type">Type of upload</label>
                <div class="col-md-6">
                    <select name="upload_type" class="form-control">
                        <option value="">Select an upload type</option>
                        <option value="create">Create Vehicles</option>
                        <option value="delete">Delete Vehicles</option>
                    </select>
                    @error('upload_type')
                    <div class="input-group-prepend">
                        <div class="alert alert-danger my-3">{!! $message !!} </div>
                    </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="file">Upload Document<br>
                    <small>Allowed file types - CSV<br>Max file size - 1MB</small>
                </label>
                <div class="col-md-6">

                    <input type="file" accept=".csv" name="file" id="file" />
                    @error('file')
                    <div class="input-group-prepend">
                        <div class="alert alert-danger my-3">{!! $message !!} </div>
                    </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="show_in_ford_pipeline">Show in Ford Pipeline?</label>
                <div class="col-md-6">
                    <select name="show_in_ford_pipeline" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    @error('show_in_ford_pipeline')
                    <div class="input-group-prepend">
                        <div class="alert alert-danger my-3">{!! $message !!} </div>
                    </div>
                    @enderror
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
