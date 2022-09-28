@extends('layouts.theme.admin.default')
@section('title', 'Profile')
@section('content')
    
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-4">
                <h3 class="font-weight-light">Update Profile</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Profile</li>
                </ol>
            </nav>
            <div class="cardData">
                <div class="card-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @else
                        @include('flash-message')
                    @endif

                    <form method="POST" action="{{ route('profile.store') }}" id="profile-data" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="col-md-12 profile-upload">
                            <div class="profile-image">
                                <div class="input-file-ca">
                                        <input type="file" name="profile_image" accept="image/*" id="ProfileImage" onchange="readURL(this);" />
                                        <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                                        @if ($errors->has('profile_image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('profile_image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                <div class="profile-name-first mb-4">
                                    
                                    @php
                                        $profileImage=(isset($user->profile_image) && $user->profile_image !='') ? trim($user->profile_image) : '';
                                    @endphp

                                    <h2>
                                        @if($profileImage != '')    
                                            <img id="profileLogo" src="{{ asset('images/profile/' . $profileImage)}}" style= "width: 100%; height: 100%;" title="{{ Auth::user()->name }}" alt="{{ Auth::user()->name }}">
                                        @else 
                                            <span id="pimage">{{$firstName}}</span> 
                                        @endif  
                                    </h2>  
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-data">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input type="text" id="inputFirstName" placeholder="Enter your first name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" required autocomplete="first_name" autofocus value="{{old('first_name', $user->first_name)}}">
                                        <label for="inputFirstName">First Name</label>
                                        @error('first_name')
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
                                        <input type="text" id="inputLastName" placeholder="Enter your last name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" required autocomplete="last_name" autofocus value="{{old('last_name', $user->last_name)}}">
                                        <label for="inputLastName">Last Name</label>
                                        @error('last_name')
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
                                        <input type="email" id="inputEmail" placeholder="Enter Email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{old('email', $user->email)}}" readonly>
                                        <label for="inputEmail">Enter Email</label>
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
                                        <input id="phone_number" type="tel" placeholder="Enter Phone Number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" required autocomplete="phone_number" maxlength="12" value="{{old('phone_number', $user->phone_number)}}">
                                        <label for="Phone Number">Enter Phone Number</label>
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
                                        <input id="address" type="text" placeholder="Enter address" class="form-control @error('address') is-invalid @enderror" name="address" required autocomplete="address" value="{{old('address', $user->address)}}">
                                        <label for="Address">Enter Address</label>
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
                                        <input id="state" type="text" class="form-control" placeholder="Enter state" name="state" required autocomplete="state" value="{{old('state', $user->state)}}">
                                        <label for="state">Enter State</label>
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
                                        <input id="zip_code" type="text" class="form-control" placeholder="Enter City" name="city" required autocomplete="city" value="{{old('city', $user->city)}}">
                                        <label for="city">Enter City</label>
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
                                        <input id="zip_code" type="text" class="form-control" placeholder="Enter zip_code" name="zip_code" required autocomplete="zip_code" value="{{old('zip_code', $user->zip_code)}}">
                                        <label for="zip_code">Enter Zip code</label>
                                        @error('zip_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="mt-4 mb-0 pro-sub">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                
@endsection
