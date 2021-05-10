@extends('layouts.main', [
    'title' => 'Add Company',
    'activePage' => 'user-manager'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">
        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Add A New Company</h1>
                <form method="POST" action="{{ route('company.create') }}">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Company Information</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_name">Company Name</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_name" id="company_name" required class="form-control" autocomplete="off" value="{{ old('company_name') }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_type">Company Type</label>
                                <div class="col-md-4">
                                    <select name="company_type" id="company_type" class="form-control" required autocomplete="off">
                                        <option value="">Please select</option>
                                        <option value="broker">Broker</option>
                                        <option value="dealer">Dealer</option>
                                        <option value="registration">Registration</option>
                                        <option value="invoice">Invoice</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_address1">Address 1</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_address1" id="company_address1" required class="form-control" autocomplete="off" value="{{ old('company_address1') }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_address2">Address 2</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_address2" id="company_address2" class="form-control" autocomplete="off" value="{{ old('company_address2') }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_city">City</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_city" id="company_city" required class="form-control" autocomplete="off" value="{{ old('company_city') }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_county">County</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_county" id="company_county" required class="form-control" autocomplete="off" value="{{ old('company_county') }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_country">Country</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_country" id="company_country" required class="form-control" autocomplete="off" value="{{ old('company_country') }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_postcode">Postcode</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_postcode" id="company_postcode" required class="form-control" autocomplete="off" value="{{ old('company_postcode') }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_email">Company Email</label>
                                <div class="col-md-4">
                                    <input type="email" name="company_email" id="company_email" required class="form-control" autocomplete="off" value="{{ old('company_email') }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_phone">Company Phone</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_phone" id="company_phone" class="form-control" autocomplete="off" value="{{ old('company_phone') }}"/>
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer text-right">
                            <a href="{{ route('company_manager') }}" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Add Company</button>
                        </div>
                    </div>
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
