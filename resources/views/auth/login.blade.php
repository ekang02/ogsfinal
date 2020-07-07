@extends('layouts.app')

@section('content')
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50">
            <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                 @csrf
                <div class="text-center mb-5">   
                    <img id="img_logo" src="{{ asset('assets/default-logo.png') }}" class="img-fluid logo-size">
                </div>
                <span class="login100-form-title p-b-33">
                    {{ __('NEU LOGIN PORTAL') }}
                </span> 
                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                    <input class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="{{ __('E-Mail Address') }}" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    
                    @error('email')
                        <span class="invalid-feedback text-center" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>

                <div class="wrap-input100 rs1 validate-input" data-validate="Password is required">
                    <input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                    

                    @error('password')
                        <span class="invalid-feedback text-center" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
                <div class="container-login100-form-btn m-t-20">
                    <button type="submit" class="login100-form-btn">
                        {{ __('Sign In') }}
                    </button>
                </div>

                <div class="text-center p-t-45 p-b-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <!-- <div class="text-center">
                    <span class="txt1">
                        Forgot Your
                    </span>

                    @if (Route::has('password.request'))
                        <a class="txt2 hov1" href="{{ route('password.request') }}">
                            {{ __('Password?') }}
                        </a>
                    @endif
                </div> -->
            </form>
        </div>
    </div>
</div>
@endsection
