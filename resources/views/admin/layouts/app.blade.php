<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'White Dashboard') }}</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('white') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('white') }}/img/favicon.png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('white') }}/css/nucleo-icons.css" rel="stylesheet" />
    <!-- CSS -->
    <link href="{{ asset('white') }}/css/white-dashboard.css?v=1.0.0" rel="stylesheet" />
    <link href="{{ asset('white') }}/css/theme.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        /* Mencegah body di-scroll */
        body.overflow-hidden {
            overflow: hidden !important;
            position: fixed;
            width: 100%;
        }

        /* Sidebar tetap di tempat */
        .sidebar.active {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1050;
            overflow-y: auto;
        }

        /* Mencegah swipe pada layar sentuh */
        .sidebar.prevent-scroll {
            touch-action: none;
        }
    </style>

</head>

<body class="white-content {{ $class ?? '' }}">
    @auth
        <div class="wrapper">
            @include('admin.layouts.navbars.sidebar')
            <div class="main-panel">
                @include('admin.layouts.navbars.navbar')

                <div class="content">
                    @yield('content')
                </div>

            </div>
            @include('admin.layouts.footer')
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        @include('admin.layouts.navbars.navbar')
        <div class="wrapper wrapper-full-page">
            <div class="full-page {{ $contentClass ?? '' }}">
                <div class="content">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>
                @include('admin.layouts.footer')
            </div>
        </div>
    @endauth
    <!-- Bootstrap JS + Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('white') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('white') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('white') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('white') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    <!-- Place this tag in your head or just before your close body tag. -->
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}
    <!-- Chart JS -->
    <script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('white') }}/js/plugins/bootstrap-notify.js"></script>

    <script src="{{ asset('white') }}/js/white-dashboard.min.js?v=1.0.0"></script>
    <script src="{{ asset('white') }}/js/theme.js"></script>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- XLSX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    @stack('js')

    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $(".navbar-toggler").on("click", function() {
                const isSidebarActive = $(".sidebar").toggleClass("active").hasClass("active");

                // Toggle body scroll lock
                $("body").toggleClass("overflow-hidden", isSidebarActive);

                // Tambahkan class untuk mencegah scroll pada sidebar
                if (isSidebarActive) {
                    $(".sidebar").addClass("prevent-scroll");
                } else {
                    $(".sidebar").removeClass("prevent-scroll");
                }
            });

            // Cegah scroll dengan touchmove (untuk perangkat layar sentuh)
            $(".sidebar").on("touchmove", function(e) {
                if ($(".sidebar").hasClass("active")) {
                    e.preventDefault();
                    console.log("Touchmove prevented");
                }
            });

            // Debugging toggle
            $(".navbar-toggler").on("click", function() {
                console.log("Sidebar toggled");
            });
        });
    </script>
    @stack('js')
</body>

</html>
