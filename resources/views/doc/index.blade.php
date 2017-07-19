@extends('layouts.doc.index')
@section('title')
    <title>{{ config('app.name', '共享笔记') }}</title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('libs/editormd/css/editormd.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/wangEditor-3.0.3/wangEditor.min.css')}}">
    <link rel="stylesheet" href="{{asset('module/doc/css/layouts/main.css')}}">
@endsection

@section('content')
    <div id="layout" class="content pure-g">
        <div id="nav" class="">
            <span class="nav-menu-button"></span>

            <div class="nav-inner">
                <div class="pure-menu">
                    <ul class="pure-menu-list">
                        <li class="pure-menu-item new-note-item">
                            <span class="middle-add-item" onclick="note.newNote('1')"></span>
                            <div class="new-doc-box">
                                <span class="add-icon"><img src="{{asset('module/doc/imgs/icon/add.png')}}"></span>
                                <span class="add-text">新建文档</span>
                                <ul class="add-list">
                                    <li onclick="note.newNote('1')">新建md文档</li>
                                    <li onclick="note.newNote('2')">新建笔记</li>
                                </ul>
                            </div>
                        </li>
                        <li class="pure-menu-item nav-newest-item active">
                            <a href="#" class="first-menu-a">最新笔记</a>
                        </li>
                        <li class="pure-menu-item nav-doc-item">
                            <a href="#" class="nav-doc-a first-menu-a is-parent" data-switch="on">
                                <span>我的文档</span>
                            </a>
                            <ul class="child-list first-child-list">

                                <li class="child-item child-item-input">
                                    <input type="text" name="add_dir1">
                                </li>
                                <li class="child-item add-dir">
                                    <a href="#"><span>+</span>新建文件夹</a>
                                </li>
                            </ul>
                            <div class="down-box">
                                <p data-type="add">新建子文件夹</p>
                                <p data-type="rename">重命名</p>
                                <p data-type="del">删除文件夹</p>
                            </div>
                        </li>
                        {{--<li class="pure-menu-item nav-share-item">--}}
                            {{--<a href="#" class="first-menu-a">我的分享</a>--}}
                        {{--</li>--}}
                        {{--<li class="pure-menu-item nav-del-item">--}}
                            {{--<a href="#" class="first-menu-a">回收站</a>--}}
                        {{--</li>--}}
                    </ul>
                    <div class="feedback">
                        <a href="{{route('feedback',['action'=> 'dashboard'])}}" target="_blank">
                            <span class="feedback-icon"><img src="{{asset('module/doc/imgs/icon/feedback.png')}}"></span>
                            <span>问题反馈与建议</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div id="list" class="">
            <div class="list-head">
                <div class="search-input-box">
                    <input type="text" class="search-input" placeholder="搜索笔记">
                </div>
                <div class="sort-box">
                    <span class="sort-box-icon"></span>
                    <ul class="sort-down-menu">
                        <li data-type="updated_at">修改时间</li>
                        <li data-type="created_at">创建时间</li>
                    </ul>
                </div>
            </div>
            <div class="list-content">
                <ul class="list-content-ul"></ul>
                <div class="list-content-null">
                    <div class="list-null">
                        <p>该目录下没有文档</p>
                        <span class="new-note" onclick="note.newNote('1')">新建文档</span>
                    </div>
                    <div class="search-null">
                        <p>搜索结果为空</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="main">
            <div class="doc-content">
                <div class="doc-content-header">
                    <div class="doc-content-title">
                        <input class="doc-title-input" type="text" placeholder="这里是标题">
                        <span class="doc-title-span" contenteditable="true"></span>
                    </div>

                    <div class="doc-content-controls">
                        <span class="more-btn" data-type="off"></span>
                        <span class="edit-btn" onclick="note.editNote()">编辑</span>
                        <span class="save-btn" onclick="note.saveNote()">保存</span>
                        <ul class="more-list">
                            <li onclick="note.htmlToPDF()">导出PDF</li>
                        </ul>
                    </div>
                </div>

                <div class="doc-content-body">
                    <div class="doc-preview-body markdown-body  editormd-preview-container">

                    </div>
                    <div class="doc-edit-body">
                        <div id="editormd" class="editor-1">
                            <textarea id="page_content" style="display:none;"></textarea>
                            <ul class="editor-theme">
                                <li>default</li>
                                <li>3024-day</li>
                                <li>3024-night</li>
                                <li>ambiance</li>
                                <li>base16-dark</li>
                                <li>base16-light</li>
                                <li>blackboard</li>
                                <li>cobalt</li>
                                <li>eclipse</li>
                                <li>erlang-dark</li>
                                <li>lesser-dark</li>
                                <li>mbo</li>
                                <li>mdn-like</li>
                                <li>midnight</li>
                                <li>monokai</li>
                                <li>neo</li>
                                <li>night</li>
                                <li>paraiso-dark</li>
                                <li>paraiso-light</li>
                                <li>pastel-on-dark</li>
                                <li>rubyblue</li>
                                <li>solarized</li>
                                <li>the-matrix</li>
                                <li>twilight</li>
                                <li>vibrant-ink</li>
                                <li>xq-dark</li>
                                <li>tomorrow-night-eighties</li>
                            </ul>
                        </div>
                        <div id="editor" class="editor-2"></div>
                    </div>
                </div>
                <div class="doc-content-null">

                </div>
            </div>
        </div>
    </div>
    <script id="pdf-tpl" type="text/html">
        <html><head><meta charset="utf-8">
            <style>.markdown-body pre{background-color:#f7f7f7;}</style>
            </head><body><div class="markdown-body">##content##</div></body>
        </html>
    </script>
    <script id="list-tpl" type="text/html">
        <% for(var i = 0; i < list.length; i++) { %>
        <li class="doc-item <% if(list[i].id === active) {%> active <% } %>" data-id="<%= list[i].id %>" data-fid="<%= list[i].f_id %>">
            <p class="doc-title">
                <% if(list[i].type === '1') {%><span class="icon-md"></span>
                <% }else{ %><span class="icon-note"></span>
                <% } %>
                <span class="list-title-text"><%= list[i].title %></span>
            </p>
            <p class="doc-time">
                <span><%= list[i].updated_at %></span>
                <span class="doc-author"> <%= list[i].author %></span>
            </p>
            <p class="doc-hover-icon">
<!--               <span class="list-share-icon" title="分享"></span>-->
                <span class="list-del-icon" title="删除"></span>
            </p>
        </li>
        <% } %>
    </script>
    <script id="add-input-tpl" type="text/html">
        <li class="child-item">
            <a href="#" class="last-menu-a" data-id="" data-pid="">
                <span class="child-menu-icon"></span>
                <span class="item-name"><input type="text"></span>
                <span class="child-menu-down" data-idx="##idx##"></span>
            </a>
        </li>
    </script>

    <script id="nav-tpl" type="text/html">
    	<% idx++; for(var i = 0; i < list.length; i++) { %>
        <li class="child-item">
        <% if(list[i].child) {%> 
			<a href="#" class="second-menu-a is-parent on" data-id="<%= list[i].id %>" data-pid="<%= list[i].p_id %>" data-switch="on">
        <% }else{ %>
            <a href="#" class="second-menu-a" data-id="<%= list[i].id %>" data-pid="<%= list[i].p_id %>">
        <% } %>
            	<span class="child-menu-open"></span>
                <span class="child-menu-icon"></span>
                <span class="item-name"><%= list[i].title %></span>
                <span class="child-menu-down" data-idx="<%= idx %>"></span>
            </a>

            <% if(list[i].child) {%>
            	<ul class="child-list">
            	<% include('nav-tpl', {list:list[i].child, idx: idx})  %>
            	</ul>
            <% } %>
        </li>
        <% } %>
    </script>
@endsection
@section('script')
    <script src="{{asset('/libs/template/template-native.js')}}"></script>
    <script src="{{asset('/libs/editormd/editormd.min.js')}}"></script>
    <script src="{{asset('/libs/nicescroll/jquery.nicescroll.min.js')}}"></script>
    <script src="{{asset('/libs/wangEditor-3.0.3/wangEditor.min.js')}}"></script>
    <script src="{{asset('/module/doc/js/index.js')}}"></script>
@endsection