layui.use(['layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    if ($('.markdown-body').length > 0) {
        var pres = $('pre');
        if (pres.length > 0) {
            pres.each(function () {
                var text = $(this).children('code').text();
                var btn = $('<span class="kg-copy">' + helper.locale('copy') + '</span>').attr('data-clipboard-text', text);
                $(this).prepend(btn);
            });
        }
    }

    var clipboard = new ClipboardJS('.kg-copy');

    clipboard.on('success', function (e) {
        layer.msg(helper.locale('copied'));
    });

});
