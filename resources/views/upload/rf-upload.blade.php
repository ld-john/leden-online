@extends('layouts.main', [
    'title' => 'Ring Fenced Stock Upload',
    'activePage' => 'rf-upload'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Import Ring Fenced Vehicles</h1>
                @include('partials.successMsg')
                <form method="POST" action="{{ route('rf_upload.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Import CSV</h6>
                            <a href="{{ asset('user-uploads/csv-uploads/ring_csv_order_upload_template.csv') }}" download class="btn btn-sm btn-info"><i class="fa-solid fa-download"></i> Download CSV Template</a>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <p>
                                        Use the below form to upload ring-fenced stock in bulk.
                                    </p>
                                    <p>
                                        You can download a template file using the button above.
                                    </p>
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
                                <label class="col-md-2 col-form-label" for="broker">Which broker?</label>
                                <div class="col-md-6">
                                    <select name="broker" class="form-control">
                                        @foreach($brokers as $broker)
                                            <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('broker')
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
