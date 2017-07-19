<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
<div id="app">

    <div class="container" style="margin-top: 150px;">

        <div class="panel panel-color {{ $data['status']?'panel-inverse':'panel-danger' }}">

            <div class="panel-heading">
                <h3 class="text-center m-t-10">{{ $data['message'] }}</h3>
            </div>

            <div class="panel-body">
                <div class="text-center">
                    <div class="alert {{ $data['status']?'alert-info':'alert-danger' }} alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        浏览器页面将在<b id="loginTime">{{ $data['jumpTime'] }}</b>秒后跳转......
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="submit" class="btn {{ $data['status']?'btn-success':'btn-danger' }} ">点击立即跳转</button>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <script>
        $(function () {
            //循环倒计时，并跳转
            var url = "{{ $data['url'] }}";
            var loginTime = parseInt($('#loginTime').text());
            console.log(loginTime);
            var time = setInterval(function () {
                loginTime = loginTime - 1;
                $('#loginTime').text(loginTime);
                if (loginTime == 0) {
                    clearInterval(time);
                    window.location.href = url;
                }
            }, 1000);
        });
        //点击跳转
        $('.btn-success').click(function () {
            window.location.href = "{{ $data['url'] }}";
        })
    </script>
</body>
</html>
