@extends('layouts.main', [
    'title' => 'Edit User',
    'activePage' => 'user-manager'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Editing User - {{ $user->firstname }} {{$user->lastname}}</h1>
                <div class="card shadow mb-4">
                    @livewire('users.edit', ['type' => 'edit', 'user' => $user])
                </div>
                @livewire('user-permission-creator', ['user' => $user])
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
