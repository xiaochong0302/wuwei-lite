layui.use(['jquery', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var showPhoneCode = function () {
        var content = '<div class="layui-font-32 layui-font-red layui-padding-5">';
        content += '<i class="iconfont icon-phone layui-padding-1 layui-font-28"></i>' + window.contact.phone;
        content += '</div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var bars = [];

    if (window.contact.phone) {
        bars.push({
            type: 'phone',
            content: '<i class="iconfont icon-phone layui-font-30"></i>',
        });
    }

    if (window.contact.email) {
        bars.push({
            type: 'email',
            icon: 'layui-icon-email',
        });
    }

    util.fixbar({
        bars: bars,
        click: function (type) {
            if (type === 'phone') {
                showPhoneCode();
            } else if (type === 'email') {
                window.location.href = 'mailto:' + window.contact.email;
            }
        }
    });

    $('.contact > .phone').on('click', function () {
        showPhoneCode();
    });

});
