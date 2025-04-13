<!DOCTYPE html>

@if (\Request::is('rtl'))
    <html dir="rtl" lang="ar">
@else
    <html lang="en">
@endif

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <title>
        Module Name to be here
    </title>

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />

    <style>
        /* Hiding the checkbox, but allowing it to be focused */
        .badgebox {
            opacity: 0;
        }

        .badgebox+.badge {
            /* Move the check mark away when unchecked */
            text-indent: -999999px;
            /* Makes the badge's width stay the same checked and unchecked */
            width: 27px;
            background: inherit;
        }

        .badgebox:focus+.badge {
            /* Set something to make the badge looks focused */
            /* This really depends on the application, in my case it was: */

            /* Adding a light border */
            box-shadow: inset 0px 0px 5px;
            /* Taking the difference out of the padding */
        }

        .badgebox:checked+.badge {
            /* Move the check mark back when checked */
            text-indent: 0;
        }



        .sidebar-icon {
            height: 2.3em;
            width: 2.3em;
            padding: 0.7em;
            margin-right:0.5em;
            border-radius: 1em;
            text-align: center;

            background-color: rgba(250, 250, 250, 1);
            color:  rgba(000, 000, 000, 0.6);
            box-shadow: 0.1em 0.1em 0.5em  rgba(000, 000, 000, 0.1);

            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;

            box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07) !important;

        }


        .module-control-input {
            height: 2.6em;
            padding: 0em 1em;
            min-width: 10em;
        }


        .small-control {
            height: 2.2em;
            margin: 0em;
        }

        button.small-control {
            height: 2.4em;
        }







    </style>



</head>

<body class="g-sidenav-show  bg-gray-100 {{ \Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '') }} ">



    @auth
        {{------ Sidebar content. The <x-slot name="sidebar" /> content goes here------}}
        {{ $sidebar?? "" }}

        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ Request::is('rtl') ? 'overflow-hidden' : '' }}">
            {{------ Navbar content. The <x-slot name="navbar" /> content goes here------}}
            {{--{{ $navbar }}--}}
            <x-core.views::layouts.navbars.auth.nav />

            <div class="container-fluid py-4">

                {{------ Header content. The <x-slot name="pageHeader" /> content goes here------}}
                {{ $pageHeader?? "" }}


                {{------ Page content. The default template [CONTENT] goes here------}}
                {{ $slot }}


                {{------ Footer content. The <x-slot name="pageFooter" /> content goes here------}}
                {{ $pageFooter?? "" }}

                <x-core.views::layouts.footers.auth.footer />
            </div>
        </main>

    @endauth





    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
            class="position-fixed bg-success rounded right-3 text-sm py-2 px-4">
            <p class="m-0">{{ session('success') }}</p>
        </div>
    @endif

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

    @stack('rtl')
    @stack('dashboard')

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>


    <!----------------------------------- Plugins ---------------------------------->
    <!------------- Flat Date Picker JS -------------->
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>

    <!------------- Flat Date Picker JS ENDS -------------->

    <!------------------- Sweet Alert JS ------------------>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--<script src="{{ asset('assets/js/plugins/sweetalert.min.js') }}"></script>-->

    <!------------ PDF File ------------>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!------------ Crest App code generator supporting code ------------>
    <script src="{{ asset('assets/js/crest-apps/code-generator-ui.js') }}"></script>

</body>

</html>
