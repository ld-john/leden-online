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
    <h1 class="h3 mb-4 text-gray-800">Editing User {{ $user->email }}</h1>
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
    <form method="POST" action="{{ route('user.update', $user->id) }}">
      @csrf
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">User Information</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="firstname">Firstname</label>
                <div class="col-md-4">
                    <input type="text" name="firstname" id="firstname" required class="form-control" autocomplete="off" value="{{ $user->firstname }}"/>
                </div>
                <label class="col-md-2 col-form-label" for="lastname">Lastname</label>
                <div class="col-md-4">
                    <input type="text" name="lastname" id="lastname" required class="form-control" autocomplete="off" value="{{ $user->lastname }}"/>
                </div>
            </div>

            <div class="form-group row">
            <label class="col-md-2 col-form-label" for="email">Email</label>
                <div class="col-md-4">
                    <input type="text" name="email" id="email" required class="form-control" autocomplete="off" value="{{ $user->email }}"/>
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <label class="col-md-2 col-form-label" for="phone">Phone</label>
                <div class="col-md-4">
                    <input type="text" name="phone" id="phone" class="form-control" autocomplete="off" placeholder="e.g. 07123 456789" value="{{ $user->phone }}"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="company_id">Company</label>
                <div class="col-md-4">
                    <select name="company_id" id="company_id" class="form-control" required autocomplete="off">
                        <option value="">Please select</option>
                        @foreach ($companies as $company)
                        <option value="{{ $company->id }}" @if ($user->company_id == $company->id) selected @endif>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-md-2 col-form-label" for="role">User Role</label>
                <div class="col-md-4">
                    <select name="role" id="role" class="form-control" required autocomplete="off">
                        <option value="">Please select</option>
                        <option value="admin" @if ($user->role == 'admin') selected @endif>Admin</option>
                        <option value="dealer" @if ($user->role == 'dealer') selected @endif>Dealer</option>
                        <option value="broker" @if ($user->role == 'broker') selected @endif>Broker</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Card Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('user_manager') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update User</button>
        </div>
      </div>
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection