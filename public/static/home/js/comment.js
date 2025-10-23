layui.use(['jquery', 'form', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    var helper = layui.helper;

    form.on('submit(add_comment)', function (data) {
        var submit = $(this);
        var $commentList = $('#comment-list');
        var $textarea = $(data.form).find('.layui-textarea');
        var $tbCommentCount = $('#toolbar-comment > .text');
        var tbCommentCount = $tbCommentCount.data('count');
        submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                $.ajax({
                    type: 'GET',
                    url: '/comment/' + res.comment.id + '/info',
                    success: function (html) {
                        tbCommentCount++;
                        $tbCommentCount.data('count', tbCommentCount).text(tbCommentCount);
                        $commentList.prepend(html);
                    }
                });
                $textarea.val('');
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
                layer.msg(res.msg);
            }
        });
        return false;
    });

    form.on('submit(reply_comment)', function (data) {
        var submit = $(this);
        var commentId = submit.data('comment-id');
        var parentId = submit.data('parent-id');
        var blockId = parentId > 0 ? parentId : commentId;
        var $commentForm = $('#comment-form-' + commentId);
        var $replyList = $('#reply-list-' + blockId);
        var $textarea = $(data.form).find('.layui-textarea');
        var $replyCount = $('#comment-' + blockId).find('.reply-count');
        var replyCount = $replyCount.data('count');
        submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                $.ajax({
                    type: 'GET',
                    url: '/comment/' + res.comment.id + '/info',
                    success: function (html) {
                        $replyList.prepend(html);
                    }
                });
                replyCount++;
                $commentForm.hide();
                $replyList.show();
                $textarea.val('');
                $replyCount.data('count', replyCount).text(replyCount);
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
                layer.msg(res.msg);
            }
        });
        return false;
    });

    $('#comment-cancel').on('click', function () {
        $('#comment-footer').hide();
    });

    $('#comment-content').on('click', function () {
        $('#comment-footer').show();
    });

    $('body').on('click', '.reply-cancel', function () {
        var id = $(this).data('id');
        $('#comment-form-' + id).hide();
    });

    $('body').on('click', '.comment-toggle', function () {
        var $this = $(this);
        var id = $this.data('id');
        var url = $this.data('url');
        var $toggleIcon = $this.find('.toggle-tips > .layui-icon');
        var $replyList = $('#comment-' + id + '>.reply-list');
        if ($replyList.hasClass('loaded')) {
            if ($toggleIcon.hasClass('layui-icon-up')) {
                $toggleIcon.removeClass('layui-icon-up').addClass('layui-icon-down');
            } else {
                $toggleIcon.removeClass('layui-icon-down').addClass('layui-icon-up');
            }
            $replyList.toggle();
        } else {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (html) {
                    $toggleIcon.removeClass('layui-icon-down').addClass('layui-icon-up');
                    $replyList.addClass('loaded').show();
                    $replyList.html(html);
                }
            });
        }
    });

    $('body').on('click', '.comment-reply', function () {
        var id = $(this).data('id');
        var $block = $('#comment-form-' + id);
        var $textarea = $block.find('textarea');
        $block.toggle();
        $textarea.focus();
    });

    $('body').on('click', '.comment-like', function () {
        var $this = $(this);
        var $likeCount = $this.next();
        var likeCount = $likeCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
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

    $('body').on('click', '.comment-delete', function () {
        var $this = $(this);
        var id = $this.data('id');
        var parentId = $this.data('parent-id');
        var $comment = $('#comment-' + id);
        var $tbCommentCount = $('#toolbar-comment > .text');
        var tbCommentCount = $tbCommentCount.data('count');
        layer.confirm(helper.locale('confirm_delete_tips'), {
            area: ['320px', '160px'],
        }, function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
                success: function () {
                    if (parentId > 0) {
                        var $replyCount = $('#comment-' + parentId).find('.reply-count');
                        var replyCount = $replyCount.data('count');
                        replyCount--;
                        $replyCount.data('count', replyCount).text(replyCount);
                    }
                    tbCommentCount--;
                    $tbCommentCount.data('count', tbCommentCount).text(tbCommentCount);
                    $comment.remove();
                    layer.closeAll();
                }
            });
        });
    });

});
