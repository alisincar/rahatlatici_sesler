<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <meta name="base_url" content="{{ URL::to('/') }}">
    <meta name="version" content="100">
</head>
<body>
<div id="app">
    <main class="py-4">
        @yield('content')
        @include('partials.navbar')
        <div class="snackbar"></div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{asset('js/main.js')}}"></script>
@stack('js')
</body>
</html>
