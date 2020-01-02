<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Menu Builder') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Styles -->
    <link href="{{ dbm_asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ dbm_asset('css/mdb.min.css') }}" rel="stylesheet">
    <link href="{{ dbm_asset('css/jquery.nestable.css') }}" rel="stylesheet">
    <link href="{{ dbm_asset('css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ dbm_asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <main class="main-menu">
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script src="{{ dbm_asset('js/app.js') }}" ></script>
<script src="{{ dbm_asset('js/mdb.min.js') }}" ></script>
<script src="{{ dbm_asset('js/jquery.nestable.js') }}" ></script>
<script src="{{ dbm_asset('js/jquery-ui.min.js') }}" ></script>

@yield('scripts')
</body>
</html>
