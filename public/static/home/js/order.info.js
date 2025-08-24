layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;
    var index = parent.layer.getFrameIndex(window.name);

    parent.layer.iframeAuto(index);

    $('.btn-cancel').on('click', function () {
        var sn = $(this).data('sn');
        var url = $(this).data('url');
        layer.confirm(helper.locale('cancel_order_tips'), {
            title: helper.locale('confirm'),
            btn: [helper.locale('yes'), helper.locale('no')],
            area: ['320px', '160px'],
        }, function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: {sn: sn},
                success: function () {
                    setTimeout(function () {
                        parent.layer.close(index);
                        top.location.href = '/uc/orders';
                    }, 1500);
                }
            });
        });
    });

});
