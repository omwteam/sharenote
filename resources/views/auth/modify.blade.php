@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">重置密码</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        <form class="login-form" action="" method="post">
                            <h3 class="font-green">修改密码</h3>
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li><span>{{ $error }}</span></li>
                                        @endforeach
                                    </ul>

                                </div>
                            @endif
                            {!! csrf_field() !!}
                            {{--<div class="form-group">--}}
                                {{--<label for="" class="control-label">头像</label>--}}
                                {{--<input type="file" class="form-control" id="dropz">--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">原始密码</label>
                                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" minlength="6" maxlength="20" placeholder="原始密码" name="oldpassword"> </div>
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">新密码</label>
                                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="新密码" name="password"> </div>
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">重复密码</label>
                                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="重复新密码" name="password_confirmation"> </div>
                            <div class="form-actions">
                                <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">确定</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{--@section('script')
    <script src="{{asset('module/doc/js/dropzone.js')}}"></script>
    <script>
        var dropz = new Dropzone("#dropz", {
            url: "handle-upload.php",
            maxFiles: 10,
            maxFilesize: 512,
            acceptedFiles: ".js,.obj,.dae"
        });
    </script>
    @endsection--}}


