<!doctype html>
<!--[if lt IE 7]>       <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>          <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>          <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>{{ config('app.name', 'Jamborow') }} | @yield('title')</title>
        <!-- General CSS Files -->
        <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
        @yield('spec-styles')
        <!-- Custom style CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

        <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    </head>
    <body>
<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <x-user-nav-bar :user="$user"></x-nav-bar>
        <x-sidebar :user="$user"></x-sidebar>
        <!-- Main Content -->

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <h2 class="font-weight-light">User Dashboard @yield('one-step')</h2>
                <div class="row">
                    <div class="card w-100">
                        <div class="col-md-12 p-0">
                            <x-breadcrumb :user="$user"></x-breadcrumb>
                        </div>
                    </div>
                </div>
         @include('alert/back/messages')
        @yield('content')

            </div>
        </section>
    </div>
        {{-- Footer --}}
        @include('partials._footer')
    </div>
</div>
<!-- General JS Scripts -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<!-- JS Libraies -->
<!-- Page Specific JS File -->
<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<!-- Custom JS File -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
@yield('spec-scripts')
<script>
    // dark light sidebar button setting

    var myswitch = document.querySelector('.dropdown-item .custom-switch-input');

    myswitch.addEventListener('change', function () {
        if (this.checked) {
            $("body").removeClass("dark-sidebar");
            $("body").addClass("light-sidebar");
        } else {
            $("body").removeClass("light-sidebar");
            $("body").addClass("dark-sidebar");
        }
    })

    // dark light layout button setting
    myswitch.addEventListener('change', function () {
        if (!this.checked) {
            $("body").removeClass("dark dark-sidebar theme-black");
            $("body").addClass("light");
            $("body").addClass("light-sidebar");
            $("body").addClass("theme-white");

            $(".choose-theme li").removeClass("active");
            $(".choose-theme li[title|='white']").addClass("active");
            $(".selectgroup-input[value|='1']").prop("checked", true);
        } else {
            $("body").removeClass("light light-sidebar theme-white");
            $("body").addClass("dark");
            $("body").addClass("dark-sidebar");
            $("body").addClass("theme-black");

            $(".choose-theme li").removeClass("active");
            $(".choose-theme li[title|='black']").addClass("active");
            $(".selectgroup-input[value|='2']").prop("checked", true);
        }
    })

</script>
</body>
</html>
