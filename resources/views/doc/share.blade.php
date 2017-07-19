@extends('layouts.doc.table')
@section('title')
    <title>我的分享</title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('module/doc/css/layouts/myShare.css')}}">
@endsection

@section('content')
    <div class="table-wrapper">
        <table class="table-list">
            <thead>
                <tr>
                    <th>笔记标题</th>
                    <th>发布日期</th>
                    <th>更新日期</th>
                    <th width="50%">已读次数</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>贤心</td>
                <td>汉族</td>
                <td>1989-10-14</td>
                <td>人生似修行</td>
            </tr>
            <tr>
                <td>张爱玲</td>
                <td>汉族</td>
                <td>1920-09-30</td>
                <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
            </tr>
            <tr>
                <td>贤心</td>
                <td>汉族</td>
                <td>1989-10-14</td>
                <td>人生似修行</td>
            </tr>
            <tr>
                <td>张爱玲</td>
                <td>汉族</td>
                <td>1920-09-30</td>
                <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
            </tr>
            <tr>
                <td>贤心</td>
                <td>汉族</td>
                <td>1989-10-14</td>
                <td>人生似修行</td>
            </tr>
            <tr>
                <td>张爱玲</td>
                <td>汉族</td>
                <td>1920-09-30</td>
                <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
            </tr>
            <tr>
                <td>贤心</td>
                <td>汉族</td>
                <td>1989-10-14</td>
                <td>人生似修行</td>
            </tr>
            <tr>
                <td>张爱玲</td>
                <td>汉族</td>
                <td>1920-09-30</td>
                <td>于千万人之中遇见你所遇见的人，于千万年之中，时间的无涯的荒野里…</td>
            </tr>
            </tbody>
        </table>
    </div>

    <script id="table-tpl" type="text/html">
        <% for(var i = 0; i < list.length; i++) { %>
        <tr>
            <td><%= list[i].title %></td>
            <td><%= list[i].created_at %></td>
            <td><%= list[i].updated_at %></td>
            <td>99</td>
        </tr>
        <% } %>
    </script>
@endsection
@section('script')
    {{--<script src="{{asset('/module/doc/js/myShare.js')}}"></script>--}}
    <script>

    </script>
@endsection