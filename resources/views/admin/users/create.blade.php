@extends('layouts.theme.admin.default')
@section('title', 'Users')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Add Users</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manage Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </nav>
    
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" id="user-data">
                @csrf
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="text" id="inputFirstName" placeholder="Enter your first name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" required autocomplete="first_name" autofocus value="{{ old('first_name') }}">
                                <label for="inputFirstName">First Name<span class="required-role">*</span></label>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="text" id="inputLastName" placeholder="Enter your last name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" required autocomplete="last_name" autofocus value="{{ old('last_name') }}">
                                <label for="inputLastName">Last Name<span class="required-role">*</span></label>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                                
                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input type="email" id="inputEmail" placeholder="Enter Email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{ old('email') }}">
                                <label for="inputEmail">Email<span class="required-role">*</span></label>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input id="phone_number" type="tel" placeholder="Enter Phone Number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" required autocomplete="phone_number" maxlength="10" value="{{ old('phone_number') }}">
                                <label for="Phone Number">Phone Number<span class="required-role">*</span></label>
                                @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input id="address" type="text" placeholder="Enter address" class="form-control @error('address') is-invalid @enderror" name="address" required autocomplete="address" value="{{ old('address') }}">
                                <label for="Address">Address<span class="required-role">*</span></label>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input id="state" type="text" class="form-control" placeholder="Enter state" name="state" required autocomplete="state" value="{{ old('state') }}">
                                <label for="state">State<span class="required-role">*</span></label>
                                @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input id="city" type="text" class="form-control" placeholder="Enter City" name="city" required autocomplete="city" value="{{ old('city') }}">
                                <label for="city">City<span class="required-role">*</span></label>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <input id="zip_code" type="text" class="form-control" placeholder="Enter zip_code" name="zip_code" required autocomplete="zip_code" maxlength="6" value="{{ old('zip_code') }}">
                                <label for="zip_code">Zip code<span class="required-role">*</span></label>
                                @error('zip_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div> 
                   
                    <div class="col-md-6">
                        <div class="mb-3 form-data">
                            <div class="form-floating mb-3 mb-md-0">
                                <select name="role_id" class="form-control custom-select">
                                    <option value="">Select Roles <span class="required-role">*</span></option>
                                    
                                    @foreach($roleName as $role)
                                        <option value="{{ $role->id }}" >{{ $role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 mb-0 pro-sub">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Add') }}
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-primary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection