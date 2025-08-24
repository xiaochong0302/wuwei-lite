layui.use(['jquery', 'layer', 'upload', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;
    var helper = layui.helper;

    upload.render({
        elem: '#change-cover',
        url: '/admin/upload/cover/img',
        accept: 'images',
        acceptMime: 'image/*',
        headers: {'X-Csrf-Token': $('meta[name="csrf-token"]').attr('content')},
        before: function () {
            layer.load();
        },
        done: function (res, index, upload) {
            $('#img-cover').attr('src', res.data.url);
            $('input[name=cover]').val(res.data.url);
            layer.closeAll('loading');
        },
        error: function (index, upload) {
            layer.msg(helper.locale('upload_failed'), {icon: 2});
        }
    });

});
