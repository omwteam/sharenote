@extends('layouts.admin')

@section('content')
    <table class="table table-bordered table-hover table-responsive">
        <thead>
        <tr>
            <th>序号</th>
            <th>名称</th>
            <th>内容简介</th>
            <th>类型</th>
            <th>IP</th>
            <th>用户</th>
            <th>处理状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($list as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ substr($item->content,0,15) }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->ip }}</td>
                <td>{{ $item->user_id }}</td>
                <td>{{ $item->active }}</td>
                <td>{{ $item->update_at }}</td>
                <td data-id="{{ $item->id }}"><button type="button" class="btn btn-info">更新状态</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
