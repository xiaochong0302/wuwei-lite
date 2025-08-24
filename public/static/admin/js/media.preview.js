layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.kg-preview').on('click', function () {

        var playUrl = $(this).data('play-url');

        var frameUrl = '/admin/vod/player?play_url=' + playUrl;

        layer.open({
            id: 'player',
            type: 2,
            title: 'Media Preview',
            resize: false,
            area: ['720px', '456px'],
            content: [frameUrl, 'no'],
        });

    });

});
