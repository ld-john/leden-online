@extends('layouts.main', [
    'title' => 'Manage Terms',
    'activePage' => 'term-editor'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Manage Terms</h1>
                <livewire:finance.term-editor />
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
