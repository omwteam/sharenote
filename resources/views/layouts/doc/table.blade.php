<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')

    @yield('style')
</head>
<body >

@yield('content')
<script src="{{asset('/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/libs/layer-v3.0.3/layer.js')}}"></script>
@yield('script')

</body>
</html>
