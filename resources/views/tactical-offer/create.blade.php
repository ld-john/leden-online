@extends('layouts.main', [
    'title' => 'Create Tactical Offer',
    'activePage' => 'create-tactical-offer'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">Create New Tactical Offer</h1>
            @livewire('tactical-offers.offer-form')
        </div>
    </div>
@endsection
