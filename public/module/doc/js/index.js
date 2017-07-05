/**
 * Created by linxin on 2017/6/26.
 */
var $input = $('.child-item-input input'),
    $layout = $('#layout'),
    $menuBtn = $('.nav-menu-button'),
    $nav = $('#nav'),
    $firstParent = $('.first-menu-a.is-parent'),
    $downBox = $('.down-box'),
    $list_ul = $('.list-content-ul'),
    $list_box = $('.list-content'),
    $doc_box = $('.doc-content');

var g_id = null,                // 定义一个全局id 用来存储当前操作目录的id
    $g_folder = null,
    editor = null,
    cur_note = null,
    dialog_type = null,         // 定义type 控制dialog的处理事件
    cur_page = 1,               // 当前笔记列表的页码
    totalPage = null,           // 笔记列表总页码
    isLoading = false;          // 是否正在加载列表



var folder = {
    init: function () {
        folder.initNav();
        folder.clickHandle();
        folder.folderHandle();
    },
    // 渲染文件夹列表
    initNav: function () {
        var tpl = $('#nav-tpl').html();
        var list = [];
        $.get('/folder/list', function (res) {
            list = res.data.categories;
            var allList = res.data.allCategories, allLen = allList.length;
            if (allLen) {
                for (var i = 0; i < allLen - 1; i++) {
                    for (var j = 1; j < allLen; j++) {
                        if (allList[i].id === allList[j].parent_id) {
                            if (!allList[i].child) {
                                allList[i].child = []
                            }
                            allList[i].child.push(allList[j]);
                        }
                    }
                }
                for (i = 0; i < list.length; i++) {
                    for (j = 0; j < allLen; j++) {
                        if (list[i].id === allList[j].parent_id) {
                            if (!list[i].child) {
                                list[i].child = []
                            }
                            list[i].child.push(allList[j]);
                        }
                    }
                }
            }
            var html = template('nav-tpl', {list: list, idx: 0, active: list[0].id});
            $('.first-child-list').prepend(html);
            note.getList(list[0].id);
            g_id = list[0].id;
            note.init();
        });
    },
    clickHandle: function () {
        // 收起侧边栏
        $menuBtn.on('click', function () {
            if ($layout.hasClass('middle')) {
                $layout.removeClass('middle');
                $menuBtn.removeClass('right');
                $firstParent.data('switch', 'on').siblings('ul').slideDown(300);
            } else {
                $layout.addClass('middle');
                $menuBtn.addClass('right');
                $firstParent.data('switch', 'off').siblings('ul').slideUp(300);
            }
        });
        if($(window).width() < 1200){
            $menuBtn.click();
        }
        $(window).resize(function(){
            if(($(this).width() < 1200 && !$('#layout').hasClass('middle')) || ($(this).width() > 1200 && $('#layout').hasClass('middle'))){
                $menuBtn.click();
            }
        });

            // 左边栏目录点击事件
        $nav.on('click', '.child-menu-open', function () {
            var self = $(this).parent(),
                ul_switch = self.data('switch');
            if (ul_switch == 'on') {
                self.data('switch', 'off').removeClass('on').siblings('ul').slideUp(300);
            } else {
                self.data('switch', 'on').addClass('on').siblings('ul').slideDown(300);
            }
        })
            // 点击我的文档目录事件
            .on('click','.first-menu-a',function () {
                var self = $(this),
                    ul_switch = self.data('switch');
                if ($layout.hasClass('middle')) {
                    $layout.removeClass('middle');
                    $menuBtn.removeClass('right');
                    self.data('switch', 'on').addClass('on').siblings('ul').slideDown(300);
                } else {
                    if (ul_switch == 'on') {
                        self.data('switch', 'off').removeClass('on').siblings('ul').slideUp(300);
                    } else {
                        self.data('switch', 'on').addClass('on').siblings('ul').slideDown(300);
                    }
                }
            })
            .on('click','.second-menu-a',function () {
                var $self = $(this);
                    g_id = $self.data('id');
                $('.child-item.active').removeClass('active');
                $self.parent().addClass('active');
                cur_page = 1;
                note.getList(g_id);
            })
            // 点击下拉菜单
            .on('click', '.child-menu-down,.first-menu-down', function (e) {
                e.stopPropagation();
                var $self = $(this),
                    idx = $self.data('idx');

                g_id = $self.parent().data('id');
                $g_folder = $self.parent().parent();

                if (!$self.hasClass('active')) {
                    $('.child-menu-down,.first-menu-down').removeClass('active');
                    $self.addClass('active');
                    $downBox.fadeIn(200).css('top', e.pageY - e.offsetY - 125);
                    // 如果是第四级目录，则不给添加子文件夹
                    var $add_p = $('.down-box p[data-type="add"]');
                    if(idx == '4'){
                        $add_p.hide();
                    }else{
                        $add_p.show();
                    }
                    // 点击其他地方则隐藏下拉框
                    $(document).one("click", function () {
                        $downBox.fadeOut(200);
                        $('.child-menu-down,.first-menu-down').removeClass('active');
                    });
                } else {
                    $self.removeClass('active');
                    $downBox.fadeOut(200);
                    $g_folder = null;
                }
            });

        // 新建文件夹
        $('.add-dir').on('click', function () {
            $(this).prev('.child-item-input').show().find('input').focus();
        });
        // 我的文档下级新建文件夹输入框失去焦点时或回车触发
        $input.on('blur keypress',function (e) {
            var $self = $(this);
            console.log(e)
            if(e.keyCode === 13){
                $self.off('blur keypress');
            }else if(e.type !== 'blur'){
                return ;
            }
            folder.addFirstFolder($self);
            $self.on('focus',function () {
                $self.off('blur keypress').on('blur keypress',function (e) {
                    var $self = $(this);

                    if (e.keyCode === 13) {
                        $self.off('blur keypress');
                    }else if(e.type !== 'blur'){
                        return ;
                    }
                    folder.addFirstFolder($self);
                })
            })
        });
    },
    // 我的文档下级新建文件夹事件
    addFirstFolder: function(self){
        var value = self.val();
        if (value) {
            $.post('/folder/add',{title: value, parent_id: 0}, function (res) {
                if(res.code === 200){
                    var list = [
                        {
                            id: res.data.id,
                            title: value,
                            parent_id: 0
                        }
                    ];
                    var html = template('nav-tpl', {list: list, idx: 0});
                    $('.child-item-input').before(html);
                }
            })
        }
        self.val('').parent().hide();
    },
    // 文件夹增删查改事件
    folderHandle: function () {
        // 选中下拉菜单事件
        $nav.on('click', '.down-box p', function (e) {
            e.stopPropagation();
            var $self = $(this),
                type = $self.data('type'),
                id = $self.data('id'),
                $icon = $('.child-menu-down.active'),
                elem = $icon.parent().parent(), text = null;

            $self.parent().hide();
            $icon.removeClass('active');

            switch (type) {
                // 新建子文件夹
                case 'add':
                    text = $('#add-input-tpl').html();
                    if (elem.find('.child-list').length === 0) {
                        elem.append('<ul class="child-list">' + text + '</ul>');
                    } else {
                        elem.children('.child-list').append(text);
                    }
                    elem.find('ul input').focus().on('blur keypress', function (e) {
                        var $self = $(this),
                            value = $self.val(),
                            $menu_a = $self.parent().parent();
                        if(e.keyCode === 13){
                            $self.off('blur');
                        }else if(e.type !== 'blur'){
                            return ;
                        }
                        if (value) {
                            var tag = $self.parent().parent().parent().parent().prev('a');
                            $(this).parent().html(value);
                            if (!tag.hasClass('is-parent')) {
                                tag.addClass('is-parent on').data('switch', 'on');
                            }
                            $.post('/folder/add', {
                                title: value,
                                parent_id: g_id
                            }, function (res) {

                                if(res.code === 200){
                                    $menu_a.removeClass('last-menu-a')
                                        .addClass('second-menu-a')
                                        .data('id',res.data.id);
                                    $menu_a = null;
                                }
                            })
                        } else {
                            $menu_a.parent().remove();
                            $menu_a = null;
                        }
                    });
                    break;
                // 文件夹重命名
                case 'rename':
                    elem = $icon.parent().find('.item-name');
                    text = elem.text();
                    elem.html('<input type="text" value="' + text + '">')
                        .find('input').focus().on('blur keypress', function (e) {
                        var $self = $(this),
                            value = $self.val();
                        if(e.keyCode === 13){
                            $self.off('blur');
                        }else if(e.type !== 'blur'){
                            return ;
                        }
                        if (value) {
                            elem.text(value);
                            $.post('/folder/update', {
                                title: value,
                                id: g_id
                            }, function (res) {
                                console.log(res);
                            })
                        } else {
                            elem.text(text);
                        }
                    }).on('click', function (e) {
                        e.stopPropagation();
                    });
                    break;
                // 删除文件夹
                case 'del':
                    $('.dialog').show();
                    dialog_type = 'del_folder';
                    break;
            }
        });
    }
};

var note = {
    init: function(){
        note.clickListEvent();
    },
    getList: function (folder_id) {
        $.get('/note/show', {id: folder_id, page: cur_page}, function (res) {
            isLoading = false;
            if(res.code === 200){
                if(res.data.data.length){
                    $doc_box.removeClass('null');
                    $list_box.removeClass('null');
                    var html = template('list-tpl', {list: res.data.data, active: res.data.data[0].id});
                    if(cur_page === 1){
                        $list_ul.html(html);
                        note.scorllHandle();
                        note.getNoteDetail(res.data.data[0].id);
                    }else{
                        $list_ul.append(html);
                        $(".list-content").getNiceScroll().resize()
                    }
                    cur_page ++;
                }else{
                    $doc_box.addClass('null');
                    $list_box.addClass('null');
                    $list_ul.html('');
                }
            }
        })
    },
    getNoteDetail: function (note_id) {
        $.get('/note/find', {id: note_id}, function (res) {
            if(res.code === 200){
                $('.doc-preview-body').html(res.data[0].content);
                $doc_box.removeClass('is_edit').addClass('no_edit');
                $('.doc-title-span').html(res.data[0].title);
                cur_note = res.data[0];
            }
        })
    },
    clickListEvent: function(){
        $list_ul.on('click','.doc-item', function () {
            var $self = $(this);
            if(!$self.hasClass('active')){
                $self.addClass('active').siblings().removeClass('active');
                var note_id = $self.data('id');
                note.getNoteDetail(note_id);
            }
        })
    },
    scorllHandle: function () {

        $list_box.on('scroll',function () {
            var ul_height = $list_ul.height(),
                box_height = $list_box.height();
            if(totalPage >= cur_page && $list_box.scrollTop() + box_height > ul_height - 50 && !isLoading){
                isLoading = true;
                note.getList();
            }
        });

        var niceScroll = $('.list-content').niceScroll({
            cursorcolor: '#999',
            autohidemode: 'leave',
            horizrailenabled: false,
            cursorborder: '0'
        });
    },
    // 初始化编辑器
    initEditor: function (value) {
        var height = $(window).height() - $('.doc-content-header').outerHeight() - 50;
        editor = null;
        editor = editormd("editormd", {
            path: "./libs/editormd/lib/",
            width: '100%',
            height: height,
            markdown: value || '',
            disabledKeyMaps: ["Ctrl-S"],
            imageUpload: true,
            imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL: "/util/upload",
            toolbarIcons: function () {
                return ["undo", "redo", "|", "bold", "del", "italic", "quote", "hr", "|", "h1", "h2", "h3",
                    "|", "list-ul", "list-ol", "link", "image", "code-block", "table", "datetime",
                    "watch", "preview", "fullscreen", "clear", "search"
                ]
            },
            onload : function() {
                var keyMap = {
                    "Ctrl-S": function(cm) {
                        note.saveNote();
                    }
                };
                this.addKeyMap(keyMap);
            }
        });
    },
    newNote: function () {
        $.post('/note/add',{
            title: '新建笔记',
            f_id: g_id
        },function(res){
            if(res.code === 200){
                $('.doc-item.active').removeClass('active');
                var list = [res.data];
                var html = template('list-tpl', {list: list, active: res.data.id});
                $list_ul.prepend(html);
                $doc_box.removeClass('null no_edit').addClass('is_edit');
                $list_box.removeClass('null');
                $('.doc-title-input').val('');
                note.initEditor();
            }else if(res.code === 403){
                alert('新建失败，已有相同标题笔记了！')
            }
        })
    },
    delNote: function () {
        var note_id = $('.doc-item.active').data('id');
        $.post('/note/del',{id: note_id}, function (res) {
            console.log(res);

        })
    },
    editNote: function () {
        $('.doc-title-input').val(cur_note.title);
        note.initEditor(cur_note.origin_content);
        $doc_box.removeClass('no_edit').addClass('is_edit');
    },
    saveNote: function () {
        var title = $('.doc-title-input').val(),
            md_cnt = editor.getMarkdown(),
            html_cnt = editor.getPreviewedHTML(),
            note_id = $('.doc-item.active').data('id');
        $.post('/note/update', {
            id: note_id,
            f_id: g_id,
            title: title,
            content: html_cnt,
            origin_content: md_cnt
        }, function (res) {
            console.log(res);
            if(res.code === 200){
                $('.doc-item.active .list-title-text').text(title);
                alert('保存成功')
            }

        })
    }
};


var main = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).keydown(function(e){
            if( e.ctrlKey  == true && e.keyCode == 83 ){
                return false;
            }
        });
        folder.init();
    },



    // dialog取消事件
    cancelDialog: function () {
        dialog_type = null;
        $('.dialog').hide();
        $g_folder = null;
    },

    // dialog确定事件
    sureDialog: function () {
        switch (dialog_type){
            // 删除目录
            case 'del_folder':
                $.post('/folder/del',{id: g_id}, function (res) {
                    if(res.code === 200){
                        $g_folder.remove();
                        main.cancelDialog();
                    }
                })
        }
    },

};
main.init();
