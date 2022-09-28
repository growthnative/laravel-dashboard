@extends('layouts.theme.admin.default')
@section('title', 'Welcome')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="px-4 py-5 my-5 text-center">
            <div class="profile-name-first mb-4">
                @php
                    $profileImage = (isset($loggedUser->profile_image) && $loggedUser->profile_image !='') ? trim($loggedUser->profile_image) : '';
                @endphp
                <h2>
                    @if($profileImage != '')
                        <img id="profileLogo" class="d-block mx-auto mb-4 main-section" alt="{{ Auth::user()->name }}" title="{{ Auth::user()->name }}" src="{{ asset('images/profile/' . $profileImage)}}">
                    @else
                        <span id="pimage">{{$firstName}}</span> 
                    @endif
                </h2>
            </div>
            
            <h1 class="display-5 fw-bold text-capitalize">{{ ucfirst(Auth::user()->first_name.' '.Auth::user()->last_name ) ?? ''}}</h1>
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4 text-uppercase">{{date('d F, Y h:i A')}} ({{config('app.timezone')}})</p>
            </div>
        </div>
    </div>
</main>
@endsection