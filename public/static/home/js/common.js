layui.extend({
    helper: '/static/lib/layui/extends/helper',
});

layui.use(['jquery', 'form', 'element', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    var helper = layui.helper;

    var $token = $('meta[name="csrf-token"]');

    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Csrf-Token', $token.attr('content'));
        },
        statusCode: {
            400: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2, anim: 6});
            },
            401: function () {
                layer.msg(helper.locale('error_401_tips'), {icon: 2, anim: 6});
            },
            403: function () {
                layer.msg(helper.locale('error_403_tips'), {icon: 2, anim: 6});
            },
            404: function () {
                layer.msg(helper.locale('error_404_tips'), {icon: 2, anim: 6});
            },
            500: function () {
                layer.msg(helper.locale('error_500_tips'), {icon: 2, anim: 6});
            }
        }
    });

    setInterval(function () {
        $.ajax({
            type: 'POST',
            url: '/token/refresh',
            success: function (res) {
                $token.attr('content', res.token);
            }
        });
    }, 600000);

    if (window.user.id > 0) {
        setInterval(function () {
            $.ajax({
                type: 'POST',
                url: '/uc/online',
            });
        }, 60000);
    }

    form.on('submit(go)', function (data) {
        var submit = $(this);
        submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                if (res.msg) {
                    layer.msg(res.msg, {icon: 1, time: 1500});
                }
                if (res.location) {
                    var target = res.target || 'self';
                    setTimeout(function () {
                        if (target === 'parent') {
                            parent.location.href = res.location;
                        } else {
                            window.location.href = res.location;
                        }
                    }, 1000);
                }
                setTimeout(function () {
                    submit.removeAttr('disabled').removeClass('layui-btn-disabled');
                }, 1500);
            },
            error: function () {
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            }
        });
        return false;
    });

    $('.kg-back').on('click', function () {
        window.history.back();
    });

    $('.nav-search').on('click', function () {
        var content = '<form action="' + $(this).data('url') + '">';
        content += '<input type="text" name="query" autocomplete="off" placeholder="' + helper.locale('search_form_tips') + '">';
        content += '</form>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: false,
            shadeClose: true,
            offset: '120px',
            maxWidth: 10000,
            skin: 'layer-search',
            content: content,
            success: function (dom) {
                var form = dom.find('form');
                var query = dom.find('input[name=query]');
                query.focus();
                $(form).submit(function () {
                    if (query.val().replace(/\s/g, '') === '') {
                        return false;
                    }
                });
            }
        });
    });

    $('body').on('click', '.layui-laypage > a', function () {
        var url = $(this).data('url');
        var target = $(this).data('target');
        if (url.length > 0 && target.length > 0) {
            helper.ajaxLoadHtml(url, target);
        }
    });

    $('body').on('click', '.kg-delete', function () {
        var url = $(this).data('url');
        var tips = $(this).data('tips');
        tips = tips || helper.locale('confirm_delete_tips');
        layer.confirm(tips, {
            title: false,
            btn: [helper.locale('confirm'), helper.locale('cancel')],
        }, function () {
            $.ajax({
                type: 'POST',
                url: url,
                success: function (res) {
                    if (res.msg !== '') {
                        layer.msg(res.msg, {icon: 1});
                    }
                    if (res.location) {
                        setTimeout(function () {
                            window.location.href = res.location;
                        }, 1500);
                    } else {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                }
            });
        });
    });

    $('body').on('click', '.kg-zoom img', function () {
        var src = $(this).attr('src').replace('!content_800', '');
        if ($('#img-viewer').length === 0) {
            $('body').append('<div class="img-viewer" id="img-viewer"></div>');
        }
        $('#img-viewer').append('<img alt="preview" src="' + src + '">').fadeIn();
    });

    $('body').on('click', '#img-viewer', function () {
        $(this).empty().fadeOut();
    });

});
