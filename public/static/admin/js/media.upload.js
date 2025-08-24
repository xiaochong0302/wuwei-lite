/**
 * @link https://www.hangge.com/blog/cache/detail_2268.html
 */
layui.use(['jquery', 'element'], function () {

    var $ = layui.jquery;
    var element = layui.element;

    WebUploader.Uploader.register({
        'before-send-file': 'beforeSendFile',
        'before-send': 'beforeSend',
    }, {
        beforeSendFile: function (file) {
            var deferred = WebUploader.Deferred();
            uploader.md5File(file)
                .progress(function (percentage) {
                    if (!isNaN(percentage)) {
                        var percent = Math.ceil(100 * percentage);
                        element.progress('md5-progress', percent + '%');
                    }
                })
                .then(function (md5) {
                    uploader.options.formData.md5 = md5;
                    $.ajax({
                        type: 'GET',
                        url: '/admin/upload/file/query',
                        data: {md5: md5},
                        success: function (res) {
                            deferred.resolve();
                        }
                    });
                });
            return deferred.promise();
        },
        beforeSend: function (block) {
            var md5 = uploader.options.formData.md5;
            var deferred = WebUploader.Deferred();
            $.ajax({
                type: 'GET',
                url: '/admin/upload/chunk/query',
                data: {
                    md5: md5,
                    chunk: block.chunk,
                    start: block.start,
                    end: block.end,
                },
                success: function (res) {
                    if (res.exists === 1) {
                        deferred.reject();
                    }
                    deferred.resolve();
                }
            });
            return deferred.promise();
        }
    });

    var uploader = WebUploader.create({
        server: '/admin/upload/chunk',
        pick: '#upload-btn',
        accept: {mimeTypes: 'video/mp4'},
        auto: true,
        chunked: true,
    });

    uploader.on('fileQueued', function (file) {
        $('#picker-block').addClass('layui-hide');
        $('#md5-progress-block').removeClass('layui-hide');
        $('#upload-progress-block').removeClass('layui-hide');
    });

    uploader.on('uploadProgress', function (file, percentage) {
        if (!isNaN(percentage)) {
            var percent = Math.ceil(100 * percentage);
            element.progress('upload-progress', percent + '%');
        }
    });

    uploader.on('uploadSuccess', function (file) {
        var md5 = uploader.options.formData.md5;
        $.ajax({
            type: 'POST',
            url: '/admin/upload/media/chunks/merge',
            data: {
                md5: md5,
                name: file.name,
                type: file.type,
                size: file.size,
            },
            success: function (res) {
                successCallback(res.upload);
            }
        });
    });

    uploader.on('uploadError', function (file) {
        console.log(file);
    });

    var successCallback = function (upload) {
        $('#upload-block').removeClass('layui-hide');
        $('input[name=upload_path]').val(upload.path);
        $('input[name=upload_id]').val(upload.id);
        element.progress('upload-progress', '100%');
        $.ajax({
            type: 'POST',
            url: $('#video-form').attr('action'),
            data: {upload_id: upload.id}
        });
    }

    // 解决遮挡不能点击问题
    setTimeout(function () {
        $('.webuploader-pick').next().css({width: '86px', height: '40px'});
    }, 1500);

});
