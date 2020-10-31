@extends('admin.layouts.home')
@section('content')
@php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
@endphp
<div class="row justify-content-center h-100">
    <div class="col-md-5">
        <div class="form-input-content">
            <div class="card card-login">
                <div class="card-header text-center d-block">
                    <a href="{{ route('admin') }}">
                        <h1 class="mb-0 p-2"><span class="brand-title">{{substr($settings->sitename,0,6)}} </span></h1>
                    </a>
                </div>
                <div class="text-center my-3">
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" autocomplete="off" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group ml-3 mb-5">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="label-checkbox ml-2 mb-0" for="checkbox1">{{ __('Remember Me') }}</label>
                        </div>
                        <input type="hidden" name="timezone" id="timezone">
                        <button class="btn btn-primary btn-block btn-lg btn-border-0" type="submit">{{ __('Login') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('public/admin_assets/main/js/jquery1.7.1.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var timezone = moment.tz.guess();
        $('#timezone').val(timezone);
    }); </script>
    <script src="{{ URL::asset('public/admin_assets/main/js/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin_assets/main/js/moment/moment-timezone.js') }}"></script>
    @endsection