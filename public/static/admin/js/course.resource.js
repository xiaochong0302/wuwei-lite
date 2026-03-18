/**
 * @link https://www.hangge.com/blog/cache/detail_2268.html
 */
layui.use(['jquery', 'element', 'helper'], function () {

    var $ = layui.jquery;
    var element = layui.element;
    var helper = layui.helper;

    WebUploader.Uploader.register({
        'before-send-file': 'beforeSendFile',
        'before-send': 'beforeSend',
    }, {
        beforeSendFile: function (file) {
            var deferred = WebUploader.Deferred();
            resUploader.md5File(file)
                .progress(function (percentage) {
                    if (!isNaN(percentage)) {
                        var percent = Math.ceil(100 * percentage);
                        element.progress('res-md5-progress', percent + '%');
                    }
                })
                .then(function (md5) {
                    resUploader.options.formData.md5 = md5;
                    $.ajax({
                        type: 'GET',
                        url: '/admin/upload/file/query',
                        data: {md5: md5},
                        success: function (res) {
                            if (res.exists === 1) {
                                successCallback(res.upload);
                                deferred.reject();
                            }
                            deferred.resolve();
                        }
                    });
                });
            return deferred.promise();
        },
        beforeSend: function (block) {
            var md5 = resUploader.options.formData.md5;
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

    var resUploader = WebUploader.create({
        server: '/admin/upload/chunk',
        pick: '#res-upload-btn',
        auto: true,
        chunked: true,
    });

    resUploader.on('fileQueued', function (file) {
        $('#res-upload-block').addClass('layui-hide');
        $('#res-md5-progress-block').removeClass('layui-hide');
        $('#res-upload-progress-block').removeClass('layui-hide');
    });

    resUploader.on('uploadProgress', function (file, percentage) {
        if (!isNaN(percentage)) {
            var percent = Math.ceil(100 * percentage);
            element.progress('upload-progress', percent + '%');
        }
    });

    resUploader.on('uploadSuccess', function (file) {
        var md5 = resUploader.options.formData.md5;
        $.ajax({
            type: 'POST',
            url: '/admin/upload/resource/chunks/merge',
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

    resUploader.on('uploadError', function (file) {
        console.log(file);
    });

    var successCallback = function (upload) {
        $.ajax({
            type: 'POST',
            url: '/admin/resource/create',
            data: {
                course_id: $('input[name=course_id]').val(),
                upload_id: upload.id,
            },
            success: function (res) {
                $('#res-upload-block').removeClass('layui-hide');
                $('#res-md5-progress-block').addClass('layui-hide');
                $('#res-upload-progress-block').addClass('layui-hide');
                loadResourceList();
            }
        });
        element.progress('res-upload-progress', '100%');
    }

    var loadResourceList = function () {
        var url = $('#res-list').data('url');
        $.get(url, function (html) {
            $('#res-list').html(html);
        });
    }

    // 解决遮挡不能点击问题
    setTimeout(function () {
        $('#upload-btn > .webuploader-pick').next().css({width: '86px', height: '40px'});
        $('#res-upload-btn > .webuploader-pick').next().css({width: '86px', height: '40px'});
    }, 1500);

    loadResourceList();

    $('body').on('change', '.res-name', function () {
        var url = $(this).data('url');
        $.post(url, {
            name: $(this).val()
        }, function (res) {
            layer.msg(res.msg, {icon: 1});
        });
    });

    $('body').on('click', '.res-btn-delete', function () {
        var url = $(this).data('url');
        layer.confirm(helper.locale('confirm_delete_tips'), {
            area: ['320px', '160px'],
        }, function () {
            $.post(url, function (res) {
                layer.msg(res.msg, {icon: 1});
                loadResourceList();
            });
        });
    });

});
