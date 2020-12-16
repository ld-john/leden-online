@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-cont">
                <h1 class="text-center"><strong>Leden Online</strong><br>Admin System</h1>

                <form method="POST" action="{{ route('login') }}">

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

                    <div class="form-group has-feedback">
                        <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">
                        <span class="fas fa-lock form-icon"></span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>                                
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
