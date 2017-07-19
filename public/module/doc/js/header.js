/**
 * Created by linxin on 2017/7/18.
 */
var DEFAULE_SKIN = 'blue';
var header = {
    init: function () {
        $('#skin').attr('class', localStorage.getItem('local_skin') || DEFAULE_SKIN ).animate({'opacity': 1},1000);
        header.switchTheme();
    },
    // 用户名下拉
    userDropDown: function (elem, event) {
        event.stopPropagation();
        var $self = $(elem);
        $self.siblings().removeClass('active');
        $self.hasClass('active') ? $self.removeClass('active') : $self.addClass('active');
        $(document).off('click').one('click', function () {
            $self.removeClass('active');
        })
    },
    // 切换主题
    switchTheme: function () {
        $('.theme-li').on('click', function (e) {
            e.stopPropagation();
            var $self = $(this),
                theme = $self.find('.theme-span').text();
            $('#skin').attr('class', theme);
            localStorage.setItem('local_skin', theme);
        });
        $('.theme-box').on('click', function (e) {
            e.stopPropagation();
            var $self = $(this);
            $self.siblings().removeClass('active');
            $self.hasClass('active') ? $self.removeClass('active') : $self.addClass('active');
            $(document).off('click').one('click', function () {
                $self.removeClass('active');
            })
        })
    }
};
header.init();