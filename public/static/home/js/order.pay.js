layui.use(['jquery', 'layer', 'util', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var util = layui.util;
    var helper = layui.helper;

    var sn = $('input[name="order.sn"]').val();
    var endTime = $('input[name="countdown.end_time"]').val();
    var serverTime = $('input[name="countdown.server_time"]').val();

    util.countdown(1000 * parseInt(endTime), 1000 * parseInt(serverTime), function (date) {
        var items = [
            {date: date[1], label: helper.locale('timer_hours')},
            {date: date[2], label: helper.locale('timer_minutes')},
            {date: date[3], label: helper.locale('timer_seconds')}
        ];
        var html = '';
        layui.each(items, function (index, item) {
            if (item.date > 0) {
                html += '<span class="value">' + item.date + '</span>';
                html += '<span class="label">' + item.label + '</span>';
            }
        });
        $('.countdown > .timer').html(html);
    });

    $('.btn-pay').on('click', function () {
        var channel = $(this).data('channel');
        var index = layer.load();
        $.ajax({
            type: 'POST',
            url: '/payment/purchase',
            data: {channel: channel, sn: sn},
            success: function (res) {
                window.location.href = res.purchase.redirect_url;
            }, error: function () {
                $('.btn-pay').removeAttr('disabled').removeClass('layui-btn-disabled');
                layer.close(index);
            }
        });
    });

});
