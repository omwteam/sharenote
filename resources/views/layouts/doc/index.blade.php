<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <link rel="stylesheet" href="{{asset('module/doc/css/header.css')}}">
    <link rel="stylesheet" href="{{asset('module/doc/css/skin.css')}}">
    @yield('style')
</head>
<body>
<section id="skin">
    <header class="header">
        <div class="logo">{{ config('app.name', '共享笔记') }}</div>
        <ul class="menu">
            <li>
                <a href="{{route('root')}}" >首页</a>
            </li>
        </ul>
        <div class="user-info" onclick="header.userDropDown(this,event)">
            <span>{{ Auth::user()->name }}</span>
            <ul class="user-down-list">
                @if (Auth::user()->auth == 2)
                    <li><a href="{{ route('modify') }}">系统管理</a></li>
                @endif
                <li><a href="{{ route('modify') }}">修改密码</a></li>
                <li><a class="logout" href="{{ route('logout') }}"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        退出登录
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </ul>
        </div>
        <div class="theme-box">
            <ul class="theme-ul">
                <li class="theme-li blue-color"><span class="theme-span">blue</span></li>
                <li class="theme-li purple-color"><span class="theme-span">purple</span></li>
                <li class="theme-li green-color"><span class="theme-span">green</span></li>
                <li class="theme-li brown-color"><span class="theme-span">brown</span></li>
                <li class="theme-li cyan-color"><span class="theme-span">cyan</span></li>
                <li class="theme-li indigo-color"><span class="theme-span">indigo</span></li>
                <li class="theme-li teal-color"><span class="theme-span">teal</span></li>
                <li class="theme-li red-color"><span class="theme-span">red</span></li>
                <li class="theme-li dark-color"><span class="theme-span">dark</span></li>
            </ul>
        </div>
    </header>
    @yield('content')
</section>
<script src="{{asset('/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/libs/layer-v3.0.3/layer.js')}}"></script>
<script src="{{asset('/module/doc/js/header.js')}}"></script>
@yield('script')
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?4dfecf0ae81170fba757c72f3cf83e11";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>

</body>
</html>
