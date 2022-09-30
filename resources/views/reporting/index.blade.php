@extends('layouts.main', [
    'title' => 'Reporting',
    'activePage' => 'reporting'
    ])

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row">
            <h1>Reporting</h1>
            @livewire('reporting.reporting-table')
        </div>
    </div>
@endsection
