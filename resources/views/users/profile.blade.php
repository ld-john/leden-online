@extends('layouts.main', [
    'title' => 'users.profile',
    'activePage' => 'users.profile'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Profile</h1>
                @livewire('users.edit', ['type' => 'profile', 'user' => Auth::user()])

                <!-- Card Header -->
                <div class="card mb-4">
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
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
