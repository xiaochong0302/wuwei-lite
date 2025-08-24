layui.define(['jquery', 'layer'], function (exports) {

    var MOD_NAME = 'helper';
    var $ = layui.jquery;
    var layer = layui.layer;

    var helper = {};

    helper.locale = function (key, params = {}) {
        var value = window.locale[key] || key;
        return value.replace(/%(\w+)%/g, function (match, key) {
            return params[key] ? params[key] : match;
        });
    };

    helper.isEmail = function (email) {
        return /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(email);
    };

    helper.getRequestId = function () {
        var id = Date.now().toString(36);
        id += Math.random().toString(36).substring(3);
        return id;
    };

    helper.ajaxLoadHtml = function (url, target) {
        var $target = $('#' + target);
        var html = '<div class="loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i></div>';
        $target.html(html);
        $.get(url, function (html) {
            $target.html(html);
        });
    };

    helper.checkLogin = function (callback) {
        if (window.user.id === '0') {
            var msg = helper.locale('login_required_tips');
            layer.msg(msg, {icon: 2, anim: 6});
            return false;
        }
        callback();
    };

    exports(MOD_NAME, helper);
});
