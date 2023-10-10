@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-cont">
                <img src="{{ asset('images/leden-group-ltd.png') }}"  alt="Leden Logo"/>
                <h1 class="text-center">Admin System</h1>

                <form method="POST" action="{{ route('login') }}">

                    @csrf

                    <x-input-field fieldname="email" fieldtype="email" fieldplaceholder="Email Address" fieldicon="fa-envelope" required autofocus/>
                    <x-input-field fieldname="password" fieldtype="password" fieldplaceholder="Password" fieldicon="fa-lock" required/>

                    <div class="row">
                        <div class="col-md-4">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                        <div class="col-md-4 text-right">
                            <a class="btn btn-link" href="{{route('request-login')}}">{{ __('Request a login') }}</a>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
