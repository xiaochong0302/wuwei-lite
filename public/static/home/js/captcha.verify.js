layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    var $account = $('#cv-email');
    var $emit = $('#cv-emit-btn');

    $emit.on('click', function () {
        var url = '/verify/captcha?email=' + $account.val();
        layer.open({
            type: 2,
            title: helper.locale('human_verify'),
            area: ['500px', '250px'],
            content: [url, 'no'],
        });
    });

    $account.on('keyup', function () {
        var account = $(this).val();
        var accountOk = helper.isEmail(account);
        if (accountOk) {
            $emit.removeClass('layui-btn-disabled').removeAttr('disabled');
        } else {
            $emit.addClass('layui-btn-disabled').attr('disabled', 'disabled');
        }
    });

});
