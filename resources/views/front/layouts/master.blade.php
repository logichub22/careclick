<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Jamborow | @yield('title')</title>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@300;400;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,500;0,700;1,100&family=M+PLUS+Rounded+1c:wght@300;400;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,500;0,700;1,100&family=M+PLUS+Rounded+1c:wght@300;400;700;900&family=Raleway:wght@300;400;500;700;900&display=swap" rel="stylesheet">
        <meta name="author" content="Afekhide Gbadamosi">
        <title>{{ config('app.name', 'Jamborow') }} | Home</title>
        <link rel="icon" href="{{ asset('img/main/favicon.ico') }}" type="image/x-icon">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <style>
            .uk-modal-body {
                padding: 0!important;
            }
            .article{
                position: relative;
                margin-top: 150px;
            }
            .underline{
                text-decoration: underline;
            }

            /*.uk-modal-body video {
                height: 394px;
                width: 700px;
            }*/
        </style>
    </head>
    <body id="to-top" class="@yield('body-class')">
    <!--Preloader-->

    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>
    <div id="introLoader" class="introloader">
        <div class="loader-container">
            <div id='stars'></div>
            <div id='stars2'></div>
            <div id='stars3'></div>
            <div class="spinner-inner">
                <img src="{{ asset('asset/img/logo.png') }}" alt="">
            </div>
        </div>
    </div>
        <!-- Header -->
        @include('front/partials/_navbar')

        <!-- Main Content -->
        @yield('content')

        @include('front/partials/_footer')
        <!-- Scripts -->
        <script src="{{ asset('js/main.js') }}"></script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '787598365293766');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=787598365293766&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->

    </body>
</html>
