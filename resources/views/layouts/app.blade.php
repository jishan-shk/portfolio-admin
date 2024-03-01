<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="admin, dashboard">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portfolio Admin">
    <meta property="og:title" content="Portfolio Admin">
    <meta property="og:description" content="Portfolio Admin">
    <meta property="og:image" content="https://dompet.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- PAGE TITLE HERE -->
    <title>Portfolio Admin</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">

    <link href="{{ secure_asset('plugins/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('plugins/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('plugins/vendor/nouislider/nouislider.min.css') }}">
    <!-- Style css -->
    <link href="{{ secure_asset('plugins/css/style.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('plugins/css/custom_style.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('plugins/parsleyjs/parsley_style.css') }}" rel="stylesheet">

    <!--- Datatable Css -->
    <link href="{{ secure_asset('plugins/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!--- JQuery min js --->
    <script src="{{secure_asset('plugins/js/jquery.min.js')}}"></script>
    <script src="{{secure_asset('plugins/parsleyjs/parsley.min.js')}}"></script>

    <!--- Sweetalert --->
    <script src="{{ secure_asset('plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <link href="{{ secure_asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <!--- Dropify  css --->
    <link href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css" rel="stylesheet">

    <!-- Pick date -->
    <link href="{{ secure_asset('plugins/vendor/pickadate/themes/default.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('plugins/vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('plugins/vendor/dropzone/dist/dropzone.css') }}" rel="stylesheet">

    <style>
        .dropify-wrapper .dropify-message p {
            font-size: 50%!important;
        }
    </style>
</head>
<body>
<!--*******************
    Preloader start
********************-->
<div id="preloader">
    <div class="waviy">
        <span style="--i:1">L</span>
        <span style="--i:2">o</span>
        <span style="--i:3">a</span>
        <span style="--i:4">d</span>
        <span style="--i:5">i</span>
        <span style="--i:6">n</span>
        <span style="--i:7">g</span>
        <span style="--i:8">.</span>
        <span style="--i:9">.</span>
        <span style="--i:10">.</span>
    </div>
</div>
<!--*******************
    Preloader end
********************-->

<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper" class="show">

    <!--**********************************
        Header start
    ***********************************-->
@include('layouts.header')
<!--**********************************
            Header end
        ***********************************-->


    <!--**********************************
        Sidebar start
    ***********************************-->
@include('layouts.sidebar')
<!--**********************************
            Sidebar end
        ***********************************-->

    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            @yield('page')
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->



    <!--**********************************
        Footer start
    ***********************************-->
    <div class="footer">

        <div class="copyright">
            <p>Copyright © Developed by <a href="{{ url('/') }}" target="_blank">Jishan Shaikh</a> 2024</p>
        </div>
    </div>
    <!--**********************************
        Footer end
    ***********************************-->




</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{ secure_asset('plugins/vendor/global/global.min.js') }}"></script>

<script src="{{ secure_asset('plugins/vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ secure_asset('plugins/vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ secure_asset('plugins/vendor/select2/js/select2.full.min.js') }}"></script>
<script src="{{ secure_asset('plugins/js/plugins-init/select2-init.js') }}"></script>

<!-- Apex Chart -->
<script src="{{ secure_asset('plugins/vendor/apexchart/apexchart.js') }}"></script>
<script src="{{ secure_asset('plugins/vendor/nouislider/nouislider.min.js') }}"></script>
<script src="{{ secure_asset('plugins/vendor/wnumb/wNumb.js') }}"></script>

<!-- Datatable JS -->
<script src="{{ secure_asset('plugins/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ secure_asset('plugins/js/plugins-init/datatables.init.js') }}"></script>

<!-- Dashboard 1 -->
<script src="{{ secure_asset('plugins/js/dashboard/dashboard-1.js') }}"></script>

<script src="{{secure_asset('plugins/js/dropify.min.js')}}"></script>

<!-- Pickdate -->
<script src="{{secure_asset('plugins/vendor/pickadate/picker.js')}}"></script>
<script src="{{secure_asset('plugins/vendor/pickadate/picker.time.js')}}"></script>
<script src="{{secure_asset('plugins/vendor/pickadate/picker.date.js')}}"></script>
<script src="{{secure_asset('plugins/js/plugins-init/pickadate-init.js')}}"></script>

<script src="{{ secure_asset('plugins/vendor/dropzone/dist/dropzone.js') }}"></script>
<script src="{{ secure_asset('plugins/js/custom.min.js') }}"></script>
<script src="{{ secure_asset('plugins/js/dlabnav-init.js') }}"></script>
<script src="{{ secure_asset('plugins/js/demo.js') }}"></script>
<script src="{{ secure_asset('plugins/js/styleSwitcher.js') }}"></script>
<script>
    $(document).ready(function (){
        $('.select2').select2({
            placeholder: "Please Select",
        });

        $('.select2-multiple').select2({
            tags: true
        });

        $('.dropify').dropify();
    });
</script>
</body>
</html>
