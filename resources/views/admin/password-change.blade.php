@extends('layouts.theme.admin.default')
@section('title', 'Change Password')
@section('content')

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-3">
                <h3 class="font-weight-light">Change Password</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                </ol>
            </nav>

            <div class="cardData">
                <div class="card-body">
                    @include('flash-message')
                    <form method="POST" action="{{ route('change-password')}}" id="change-password">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class=" mb-3 form-data">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input type="password" id="inputCurrentPassword" placeholder="Enter Old Password" class="form-control @error('password') is-invalid @enderror" name="current_password" required autocomplete="current_password">
                                        <label for="current-password">Old Password</label>
                                        @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                   
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-data">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input type="password" id="inputCurrentPassword" placeholder="Enter New Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="password">
                                        <label for="current-password">New Password</label>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-data">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input type="password" id="inputCurrentPassword" placeholder="Enter Confirm Password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="password_confirmation">
                                        <label for="confirm-password">Confirm New Password</label>
                                        @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button id="click_here_button" type="submit" class="btn btn-primary mr-2 form-submit-btn custom-btn-admin">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection