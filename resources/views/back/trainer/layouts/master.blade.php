<!doctype html>
<!--[if lt IE 7]>       <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>          <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>          <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jamborow') }} | @yield('title')</title>
        <link rel="icon" href="{{ asset('img/main/favicon.ico') }}" type="image/x-icon">

        @include('back/trainer/partials/_head')
    </head>
    <body class="fixed-sidebar fixed-header fixed-footer skin-default">

        <!-- Whole Wrapper -->
        <div class="wrapper">

            <div class="preloader"></div>

            <!-- Left Sidebar (Main) -->
            @include('back/trainer/partials/_sidebar')
            <!--// End Left Sidebar (Main) //-->

            <!-- Right Sidebar -->
            @include('back/trainer/partials/_right-sidebar')
            <!--// End Right Sidebar //-->

            <!-- Header -->
            @include('back/trainer/partials/_header')
            <!--// End Header //-->

            <!-- Main Content -->
            <div class="site-content">
                <!-- Inner Content -->
                <div class="content-area py-1">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        @yield('page-nav')
                        <!--// End Breadcrumb //-->
                        <!-- Flash Messages -->
                        @include('alert/back/messages')
                        <!-- Page Content -->
                        @yield('content')
                        <!--// End Page Content //-->
                    </div>
                </div>
                <!--// End Inner Content //-->

                <!-- Footer -->
                @include('back/trainer/partials/_footer')
                <!--// End Footer //-->
            </div>
            <!--// End Main Content //-->

        </div>
        <!--// End Wrapper //-->
        
        <!-- Scripts -->
        @include('back/trainer/partials/_scripts')
    </body>
</html>
