@extends('layouts.main', [
    'title' => 'Fit Options - CSV Upload',
    'activePage' => 'fit-options-upload'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Fit Options Upload</h1>
                <form action="{{ route('import_parse') }}" method="POST" class="mb-4" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Import CSV</h6>
                            <a href="{{ asset('user-uploads/csv-uploads/csv_order_upload_template.csv') }}" download class="btn btn-sm btn-info"><i class="fa-solid fa-download"></i> Download CSV Template</a>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <p>
                                        Use the below form to upload Fit Options in bulk.
                                    </p>
                                    <p>
                                        You can download a template file using the button above.
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="csv_file">Upload Document<br>
                                    <small>Allowed file types - CSV<br>Max file size - 1MB</small>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" accept=".csv" name="csv_file" id="file" />
                                    @error('file')
                                    <div class="input-group-prepend">
                                        <div class="alert alert-danger my-3">{!! $message !!} </div>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group-row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="yes" id="header" name="header" checked>
                                    <label class="form-check-label" for="header">
                                        File contains header row?
                                    </label>
                                </div>
                            </div>
                            <input type="submit" value="Upload" class="mt-4 btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
