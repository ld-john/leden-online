@extends('layouts.main', [
    'title' => 'Unauthorised',
    'activePage' => ''
    ])

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- 404 Error Text -->
<div class="text-center">
    <div class="error mx-auto" data-text="401">401</div>
    <p class="lead text-gray-800 mb-5">Unauthorised Access</p>
    <p class="text-gray-500 mb-0">You do not have access to view this page!</p>
    <a href="{{ route('dashboard') }}">&larr; Back to Dashboard</a>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

@endsection