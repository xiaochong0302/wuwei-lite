layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.kg-status-history').on('click', function () {
        var title = $(this).attr('title');
        layer.open({
            type: 2,
            title: title,
            content: $(this).data('url'),
            area: ['640px', '320px'],
        });
    });

});
