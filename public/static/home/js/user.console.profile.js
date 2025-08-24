layui.use(['jquery', 'layer', 'upload', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;
    var helper = layui.helper;

    upload.render({
        elem: '#change-avatar',
        url: '/upload/avatar/img',
        accept: 'images',
        acceptMime: 'image/*',
        size: 512,
        auto: false,
        before: function () {
            layer.load();
        },
        choose: function (obj) {
            var flag = true;
            obj.preview(function (index, file, result) {
                console.log(file);
                var img = new Image();
                img.src = result;
                img.onload = function () {
                    if (img.width < 1000 && img.height < 1000) {
                        obj.upload(index, file);
                    } else {
                        flag = false;
                        layer.msg(helper.locale('avatar_too_large_tips'), {icon: 2});
                        return false;
                    }
                };
                return flag;
            });
        },
        done: function (res) {
            $('#img-avatar').attr('src', res.data.url);
            $('input[name=avatar]').val(res.data.url);
            layer.closeAll('loading');
        },
        error: function () {
            layer.msg(helper.locale('upload_failed'), {icon: 2});
        }
    });

});
