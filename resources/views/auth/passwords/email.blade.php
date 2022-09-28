@extends('layouts.theme.auth.default')
@section('title', 'Password Reset')
@section('content')

<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Password Recovery</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                    <form method="POST" action="{{ route('password.email') }}" id="password-forgot">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 form-data">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input id="email" type="email" placeholder="name@example.com" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <label for="email">Email address</label>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="{{ route('login') }}">Return to login</a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection