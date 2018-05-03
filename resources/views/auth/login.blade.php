@extends('layouts.auth')

@section('content')
    {{--  --}}
    <div class="card">
        {{$errors}}
        <div class="body">
            <form method="POST" action="{{ route('admin.postLogin') }}">
            @csrf
                <div class="msg">Sign in to start your session</div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input type="text" class="form-control" name="email" placeholder="{{ __('Email') }}" required autofocus>
                    </div>
                </div>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif


                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required>
                    </div>
                </div>

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif


                <div class="row">
                    <div class="col-xs-8 p-t-5">
                        <input type="checkbox" id="rememberme" name="remember" {{ old('remember') ? 'checked' : '' }} class="filled-in chk-col-pink">
                        <label for="rememberme">Remember Me</label>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-block bg-pink waves-effect" type="submit" style="background-image: linear-gradient( 135deg, #55321C 0%, #d0bcaf 100%);">{{__('SIGN IN')}}</button>
                    </div>
                </div>
                <div class="row m-t-15 m-b--20">
                    <div class="col-xs-6">
                        {{-- <a href="sign-up.html">Register Now!</a> --}}
                    </div>
                    <div class="col-xs-6 align-right">
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--  --}}
@endsection
