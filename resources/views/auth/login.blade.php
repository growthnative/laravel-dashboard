@extends('layouts.theme.auth.default')
@section('title', 'Login')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Login</h3>
                </div>
                @include('flash-message')
                <div class="card-body">
                    <form method="POST" action="{{ route('login.post') }}" id="user-login">
                        @csrf
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 form-data">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input type="email" id="inputEmail" placeholder="name@example.com" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        <label for="inputEmail">Email address</label>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3 form-data">
                                    <div class="form-floating mb-3">
                                        <input id="inputPassword" type="password" placeholder="Create a password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        <a href="javascript:;" class="eye-icon password"><i class="fa fa-eye"></i></a>
                                        <label for="inputPassword">Password</label>
                                        
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3 rem-data">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                        </div>
                            
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            @if (Route::has('password.request'))
                            <a class="small btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                            @endif

                            <button type="submit" class="btn btn-primary mb-2">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>

                    <!-- <div class="card-footer text-center py-3">
                        <div class="small"><a href="{{ url('auth/google') }}">Login with google</a></div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection