@extends('layouts.main', [
    'title' => 'Messages',
    'activePage' => 'messages'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-xxl">

<!-- Content Row -->

        <h1 class="h3 mb-4 text-gray-800">Messages</h1>
        @if (!empty(session('successMsg')))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('successMsg') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        @endif
        @livewire('messages.messages')

    </div>


<!-- /.container-fluid -->

@endsection
