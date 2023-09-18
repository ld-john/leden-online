@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-cont">
                    <img src="{{ asset('images/leden-group-ltd.png') }}"  alt="Leden Logo"/>
                    <h1 class="text-center">Admin System - Request a Login</h1>

                    <form method="POST" action="{{ route('request-login') }}">
                        @csrf
                        <x-input-field fieldname="name" fieldtype="text" fieldplaceholder="Name" />
                        <x-input-field fieldname="broker" fieldtype="text" fieldplaceholder="Company Name" />
                        <x-input-field fieldname="email" fieldtype="email" fieldplaceholder="Email Address" fieldicon="fa-envelope" required />
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit request') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection