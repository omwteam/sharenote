@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">问题反馈与建议</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('feedback.store') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">

                                <label for="title" class="col-md-2 control-label">标题</label>

                                <input type="hidden" name="action" value="{{$action}}">
                                <div class="col-md-10">
                                    <input id="title" type="text" class="form-control " name="title"
                                           value="{{ old('title') }}" maxlength="60" placeholder="请输入标题" required autofocus>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">

                                <label for="type" class="col-md-2 control-label">类型</label>


                                <div class="col-md-4">

                                    <select name="type" class="form-control " id="type">
                                        <option value="1">问题</option>
                                        <option value="2">建议</option>
                                        <option value="3">其他</option>
                                    </select>

                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">

                                <label for="content" class="col-md-2 control-label">详细描述</label>


                                <div class="col-md-10">
                                    <textarea  class="form-control " name="content" rows="15" maxlength="1000" placeholder="详细描述您要反馈的问题与建议，谢谢您的支持与鼓励" required></textarea>
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">

                                <label for="title" class="col-md-2 control-label">联系您</label>


                                <div class="col-md-10">
                                    <input id="title" type="text" class="form-control " name="contact"
                                           value="{{ old('contact') }}" maxlength="60" placeholder="邮箱/手机号码【可以不填】"  autofocus>

                                    @if ($errors->has('contact'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="" style="padding: 10px 15px 10px 60px;">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        提交
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
