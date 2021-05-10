@extends('layouts.main', [
    'title' => 'Profile',
    'activePage' => 'profile'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Profile</h1>
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
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Profile Information</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="firstname">Firstname</label>
                                <div class="col-md-4">
                                    <input type="text" name="firstname" id="firstname" required class="form-control" autocomplete="off" value="{{ Auth::user()->firstname }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="lastname">Lastname</label>
                                <div class="col-md-4">
                                    <input type="text" name="lastname" id="lastname" required class="form-control" autocomplete="off" value="{{ Auth::user()->lastname }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="email">Email</label>
                                <div class="col-md-4">
                                    <input type="text" name="email" disabled id="email" required class="form-control" autocomplete="off" value="{{ Auth::user()->email }}"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="phone">Phone</label>
                                <div class="col-md-4">
                                    <input type="text" name="phone" id="phone" class="form-control" autocomplete="off" placeholder="e.g. 07123 456789" value="{{ Auth::user()->phone }}"/>
                                </div>
                            </div>
                        </div>
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Change Password</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="password">New Password</label>
                                <div class="col-md-4">
                                    <input type="password" name="password" id="password" class="form-control" autocomplete="off"/>
                                </div>
                                <label class="col-md-2 col-form-label" for="password_confirmation">Confirm New Password</label>
                                <div class="col-md-4">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Company Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-6">
                                <table border="0" cellspacing="0" cellpadding="20" width="100%">
                                    <tr>
                                        <td width="30%" valign="top">
                                            <strong>Company Name:</strong>
                                        </td>
                                        <td valign="top">
                                            {{ $company->company_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30%" valign="top">
                                            <strong>Company Address:</strong>
                                        </td>
                                        <td valign="top">
                                            {{$company->company_address1}}<br>
                                            {{$company->company_address2}}<br>
                                            {{$company->company_city}}<br>
                                            {{$company->company_county}}<br>
                                            {{$company->company_country}}<br>
                                            {{$company->company_postcode}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" valign="top">
                                            *If these details are incorrect, please contact a Leden staff member.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer text-right">
                            <button class="btn btn-primary" type="submit">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
