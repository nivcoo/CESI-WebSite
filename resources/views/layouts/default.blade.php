<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}} - {{$website_name}}</title>
    <meta name="robots" content="follow, index, all">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Cesi Intership">
    <meta property="og:description" content="CESI RALLYE">
    <meta name="author" content="Lilian, Nicolas, Alexis">
    <meta property="og:title" content="{{$title}} - {{$website_name}}">
    <meta property="og:type" content="Site">
    <meta property="og:image" content="{{ asset('img/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}"/>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome-5/css/all.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link href="{{ asset('css/admin/adminlte.min.css') }}" rel="stylesheet" type="text/css" >
    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
</head>
<body class="hold-transition login-page">


@yield('content')
<script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery/jquery-ui.min.js') }}"></script>

<script src="{{ asset('js/jquery/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/bootstrap/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/admin/sparkline.js') }}"></script>
<script src="{{ asset('js/jquery/jquery.knob.min.js') }}"></script>
<script src="{{ asset('js/moment/moment.min.js') }}"></script>
<script src="{{ asset('js/bootstrap/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('js/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('js/jquery/jquery.overlayScrollbars.min.js') }}"></script>


<script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/admin/adminlte.min.js') }}"></script>


<script>

    const LOADING_MSG = "Loading";
    const ERROR_MSG = "Error";
    const INTERNAL_ERROR_MSG = "Internal error, please retry";
    const FORBIDDEN_ERROR_MSG = "Access is forbidden"
    const SUCCESS_MSG = "Success";

</script>
<script src="{{ asset('js/form.js') }}"></script>
</body>
</html>

