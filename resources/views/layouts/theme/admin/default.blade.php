<!DOCTYPE html>
<html lang="en">
@include('layouts.theme.admin.header')

<body class="sb-nav-fixed">

    @include('layouts.theme.admin.navbar')

    <div id="layoutSidenav">
        @include('layouts.theme.admin.sidebar')
        <div id="layoutSidenav_content">

            @yield('content')

            @include('layouts.theme.admin.footer')

            <!-- Include custom/external jQuery -->
            @yield('jQuery')
            @yield('script')
</body>

</html>