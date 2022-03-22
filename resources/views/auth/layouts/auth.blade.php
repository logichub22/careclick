<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jamborow') }} | @yield('title')</title>
        <link rel="icon" href="{{ asset('img/main/favicon.ico') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/main/bootstrap.min.css') }}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/main/jamborow.css') }}">

        <link rel="stylesheet" href="{{ asset('css/front/flags.css') }}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>

        <!-- Auth CSS -->
        <link rel="stylesheet" href="{{ asset('css/front/auth.css') }}">
         <!-- Auth Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('css/front/auth-responsive.css') }}">

        @stack('styles')

    </head>
    <body id="to-top">

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        
        
        <!-- Scripts -->
        <script src="{{ asset('js/main/jquery.min.js') }}"></script>
        <script src="{{ asset('js/main/popper.min.js') }}"></script>
        <script src="{{ asset('js/main/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/main/jamborow.js') }}"></script>

        <!-- Theme JS -->
        <script src="{{ asset('js/front/auth.js') }}"></script>

        <script src="{{ asset('js/front/jquery.flagstrap.min.js') }}"></script>

        @stack('scripts')
    </body>
</html>
