@extends('layouts.main', [
    'title' => 'Manage News and Promos',
    'activePage' => 'manage-updates'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <h1 class="h3 mb-4 text-gray-800">Manage News and Promos</h1>
        @livewire('update.manage-updates')
    </div>
@endsection
