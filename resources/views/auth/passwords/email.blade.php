@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-cont">
                <h1 class="text-center"><strong>Leden Online</strong><br>Admin System</h1>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">

                    @csrf
                    <div class="form-group has-feedback">
                        <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <span class="fas fa-envelope form-icon"></span>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('Back to login') }}
                            </a>
                        </div>
                        <div class="col-md-7 text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}
                            </button>                             
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
