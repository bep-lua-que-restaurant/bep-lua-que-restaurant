{{-- Chứa khung giao diện bên admin --}}
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from uena.dexignzone.com/laravel/demo/index by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:02:13 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="3pvIDLSX29wd150Hxi6MALt7d8F4BtSQooZ74xzA">
    <meta name="description" content="Some description for the page" />
    <title>@yield('title')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin') }}/images/favicon.png">
    <link href="{{ asset('admin') }}/vendor/chartist/css/chartist.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin') }}/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('admin') }}/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        @include('admin.nav-header')
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->

        <!--**********************************
            Chat box start
        ***********************************-->
        @include('admin.chatbox')
        <!--**********************************
            Chat box End
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        @include('admin.header')
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <!--**********************************
    Sidebar start
***********************************-->
        @include('admin.sidebar')
        <!--**********************************
    Sidebar end
***********************************--> <!--**********************************
            Sidebar end
        ***********************************-->



        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            @yield('content')
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->

        <!--**********************************
  Footer start
***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="https://dexignzone.com/"
                        target="_blank">DexignZone</a> 2022</p>
            </div>
        </div>
        <!--**********************************
  Footer end
***********************************-->
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
    <script src="{{ asset('admin') }}/vendor/global/global.min.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript">
    </script>
{{--    <script src="{{ asset('admin') }}/vendor/chart.js/Chart.bundle.min.js" type="text/javascript"></script>--}}
    <script src="{{ asset('admin') }}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/dashboard/dashboard-1.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/custom.min.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/deznav-init.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/demo.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/styleSwitcher.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/app.js" type="text/javascript"></script>


</body>

<!-- Mirrored from uena.dexignzone.com/laravel/demo/index by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:04:22 GMT -->

</html>
