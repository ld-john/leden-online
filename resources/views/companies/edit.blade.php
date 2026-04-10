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
                <livewire:company.edit :company="$company" />
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
                        @livewire('customer.dealer-discount-editor', ['dealer' => $company->id])
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
    <!-- /.container-fluid -->

@endsection
