@extends('layouts.theme.auth.default')
@section('title', 'Reset Password')
@section('content')
<main>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">Set Password</h3>
                    </div>
                    <div class="card-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        @endif
                       
                        <form method="POST" action="{{ route('setPassword') }}" id="setPassword">
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="email" type="email" placeholder="name@example.com" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $userEmail['email'] ?? old('email') }}"  autocomplete="email" autofocus readonly>
                                <label for="email">Email address</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-data">
                                <div class="form-floating mb-3">
                                    <input id="inputPassword" type="password" placeholder="Create a password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
                                    <a href="javascript:;" class="eye-icon password"><i class="fa fa-eye"></i></a>
                                        <label for="inputPassword">Password</label>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                            </div>

                            <div class="mb-3 form-data">
                                <div class="form-floating mb-3">
                                    <input id="confirmPsd" type="password" placeholder="Confirm password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"  autocomplete="password_confirmation">                                            
                                    <a href="javascript:;" class="icon-password"><i class="fa fa-eye"></i></a>
                                    <label for="confirmPsd">Confirm Password</label>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Set Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
