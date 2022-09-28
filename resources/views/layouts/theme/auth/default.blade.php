<!DOCTYPE html>
<html lang="en">

@include('layouts.theme.auth.header')

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            
        @yield('content')

        @include('layouts.theme.auth.footer')