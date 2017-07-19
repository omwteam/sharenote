<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'STIP平台') }}</title>
    <link rel="stylesheet" href="{{asset('libs/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/editormd/css/editormd.min.css')}}">
    <!-- Styles -->
    <style>
        .feedback {
            position: fixed;
            top: 50%;
            right: 30px;
            box-sizing: content-box;
            width: 100px;
            height: 25px;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            line-height: 25px;

        }
        .feedback:hover {
            background-color: gainsboro;
            cursor: pointer;
        }
    </style>
</head>
<body >
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('root')}}">{{config('app.name')}}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                {{--<li><a href="http://doc.omwteam.com">ShowDoc文档 </a></li>--}}

            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Route::has('login'))
                    @if (Auth::check())
                        <li><a href="{{ url('/dashboard') }}">进入主页</a></li>

                    @else
                        <li><a href="{{ url('/login') }}">登录</a></li>
                        <li><a href="{{ url('/register') }}">注册</a></li>
                    @endif
                @endif

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<div class="container">

    <div id="test-editormd" class="editormd-onlyread"></div>


</div>
<a class="feedback" href="{{route('feedback')}}" title="问题反馈与建议">问题反馈与建议</a>
<script src="{{asset('libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('libs/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('/libs/editormd/editormd.min.js')}}"></script>
<script>

$.get("@php echo route('getReadme'); @endphp", function(md){
    testEditor = editormd("test-editormd", {
        width: "100%",
        height: 900,
        path : "./libs/editormd/lib/",
        readOnly: true,
        markdown : md,
        taskList : true,
//        theme : "dark",
//        previewTheme : "dark",
//        editorTheme : "pastel-on-dark",

//        codeFold : true,

        //syncScrolling : false,
//        saveHTMLToTextarea : true,    // 保存 HTML 到 Textarea
//        searchReplace : true,
//        watch : false,                // 关闭实时预览
//        htmlDecode : "style,script,iframe|on*",            // 开启 HTML 标签解析，为了安全性，默认不开启
//        toolbar  : true,             //关闭工具栏
//        previewCodeHighlight : false, // 关闭预览 HTML 的代码块高亮，默认开启
//        emoji : true,

//        tocm            : true,         // Using [TOCM]
//        tex : true,                   // 开启科学公式TeX语言支持，默认关闭
//        flowChart : true,             // 开启流程图支持，默认关闭
//        sequenceDiagram : true,       // 开启时序/序列图支持，默认关闭,
//        dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为true
//        dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
//        dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
//        dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为0.1
//        dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为#fff
//        imageUpload : true,
//        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
//        imageUploadURL : "./php/upload.php",

        onload : function() {
            testEditor.previewing();
            console.log('onload', this);
        }
    });
});




</script>
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
