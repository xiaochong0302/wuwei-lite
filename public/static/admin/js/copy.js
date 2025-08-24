layui.use(['layer','helper'], function () {

    var layer = layui.layer;
    var helper = layui.helper;
    var clipboard = new ClipboardJS('.kg-copy');

    clipboard.on('success', function (e) {
        layer.msg(helper.locale('copied'));
    });

});
