layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    $('.icon-praise').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        var $likeCount = $parent.next();
        var likeCount = $likeCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $parent.data('url'),
                success: function () {
                    if ($this.hasClass('liked')) {
                        $this.removeClass('liked');
                        likeCount--;
                    } else {
                        $this.addClass('liked');
                        likeCount++;
                    }
                    $likeCount.data('count', likeCount).text(likeCount);
                }
            });
        });
    });

    $('.sidebar-lesson').on('click', function () {
        if ($(this).hasClass('deny')) {
            return false;
        }
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    $('.icon-reply').on('click', function () {
        $('html').scrollTop($('#comment-anchor').offset().top);
    });

    var $commentList = $('#comment-list');

    if ($commentList.length > 0) {
        helper.ajaxLoadHtml($commentList.data('url'), $commentList.attr('id'));
    }

});
