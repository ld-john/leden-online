@extends('layouts.main', [
    'title' => 'Manage Ford Report Locations',
    'activePage' => 'manage-locations'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <h1 class="h3 mb-4 text-gray-800">Manage Ford Report Locations</h1>
        @livewire('locations.manage-locations')
    </div>
@endsection
