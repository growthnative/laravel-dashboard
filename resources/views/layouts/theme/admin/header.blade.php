<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Title -->
	<title>{{config('app.name')}} - @yield('title')</title>

	<!-- Styles -->
	
	<link href="{{ asset('css/datatables.css') }}" rel="stylesheet" />
	<link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
	
    

	<!-- Scripts -->
	<script src="{{ asset('js/all.js') }}" crossorigin="anonymous"></script>
</head>