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
                <livewire:company.company-create />
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
