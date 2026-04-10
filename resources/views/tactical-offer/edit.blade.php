@extends('layouts.main', [
    'title' => 'Edit Tactical Offer',
    'activePage' => 'edit-tactical-offer'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">Edit Tactical Offer</h1>
            @livewire('tactical-offers.offer-form', ['offer' => $tacticalOffer])
        </div>
    </div>
@endsection
