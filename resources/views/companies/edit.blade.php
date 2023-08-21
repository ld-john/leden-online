@extends('layouts.main', [
    'title' => 'Edit Company',
    'activePage' => 'user-manager'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Edit Company</h1>
                <form method="POST" action="{{ route('company.update', $company->id) }}">
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
                                    <input type="text" name="company_name" id="company_name" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_name }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_type">Company Type</label>
                                <div class="col-md-4">
                                    <select name="company_type" id="company_type" class="form-control" required
                                            autocomplete="off">
                                        <option value="">Please select</option>
                                        <option @if ($company->company_type == 'broker') selected @endif value="broker">
                                            Broker
                                        </option>
                                        <option @if ($company->company_type == 'dealer') selected @endif value="dealer">
                                            Dealer
                                        </option>
                                        <option @if ($company->company_type == 'invoice') selected
                                                @endif value="invoice">Invoice
                                        </option>
                                        <option @if ($company->company_type == 'registration') selected
                                                @endif value="registration">Registration
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_address1">Address 1</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_address1" id="company_address1" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_address1 }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_address2">Address 2</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_address2" id="company_address2"
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_address2 }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_city">City</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_city" id="company_city" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_city }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_county">County</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_county" id="company_county" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_county }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_country">Country</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_country" id="company_country" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_country }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_postcode">Postcode</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_postcode" id="company_postcode" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_postcode }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_email">Company Email</label>
                                <div class="col-md-4">
                                    <input type="email" name="company_email" id="company_email" required
                                           class="form-control" autocomplete="off"
                                           value="{{ $company->company_email }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="company_phone">Company Phone</label>
                                <div class="col-md-4">
                                    <input type="text" name="company_phone" id="company_phone" class="form-control"
                                           autocomplete="off" value="{{ $company->company_phone }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-form-label">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="fleet_procure_member" id="fleet_procure_member" @if($company->fleet_procure_member) checked @endif>
                                        <label class="form-check-label" for="fleet_procure_member">Fleet Procure Member</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer text-right">
                            <a href="{{ route('company_manager') }}" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Update Company</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        @if($company->company_type)
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Edit Dealer Discount</h6>
                    </div>
                    <div class="card-body">
                        <livewire:customer.dealer-discount-editor :dealer="$company->id"></livewire:customer.dealer-discount-editor>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
    <!-- /.container-fluid -->

@endsection
