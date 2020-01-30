<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Database Manager') }}</title>

    <!-- VENDOR CSS -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ dbm_asset('img/favicon.png') }}">
    <link href="{{ dbm_asset('css/app.css') }}" rel="stylesheet">

     <!--  fontawesome script -->
    <script src="https://kit.fontawesome.com/30496fedc5.js" crossorigin="anonymous"></script>
    <!-- MAIN CSS -->
    <link href="{{ dbm_asset('css/jquery.nestable.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ dbm_asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ dbm_asset('css/responsive.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="app" base-path="{{ dbm_base_path() }}" prefix="{{ dbm_prefix() }}">
        <!-- WRAPPER -->
        <div id="wrapper">
            <!-- MAIN -->
            <database-app driver="{{ dbm_driver() }}"></database-app>
            <!-- END MAIN -->
        </div>
        <!-- END WRAPPER -->
    </div>
    <!-- Javascript -->

    <script src="{{ dbm_asset('js/app.js') }}" ></script>
    <script src="{{ dbm_asset('js/custom.js') }}"></script>
    <script>
        $(document).ready(function() {
             // active class add in form label
            $('.cs-form-group').on('click',function(){
               $('.cs-form-group .cs-label').removeClass('active');
               $(this).find('.cs-label').addClass('active');
           });
        });
    </script>
</body>
</html>
