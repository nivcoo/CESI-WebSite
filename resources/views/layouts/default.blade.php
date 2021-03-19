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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <script src="https://kit.fontawesome.com/fb032ab5a6.js" crossorigin="anonymous" async></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css" integrity="sha512-8vq2g5nHE062j3xor4XxPeZiPjmRDh6wlufQlfC6pdQ/9urJkU07NM0tEREeymP++NczacJ/Q59ul+/K2eYvcg==" crossorigin="anonymous" />
    <link href="{{ asset('css/admin/adminlte.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
</head>
<body class="hold-transition login-page">


@yield('content')

<script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/boostrap/bootstrap.bundle.min.js') }}"></script>
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

