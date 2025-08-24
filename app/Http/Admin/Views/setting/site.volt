{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'admin.setting.site'}) %}
    {% set offline_tips_display = site.status == 'offline' ? 'display:block' : 'display:none' %}
    {% set analytics_script_display = site.analytics_enabled == 1 ? 'display:block' : 'display:none' %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('site_basic_info') }}</li>
            <li>{{ locale._('site_configuration') }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/site_basic_info') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/site_configuration') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'layer', 'upload', 'helper'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var upload = layui.upload;
            var helper = layui.helper;

            form.on('radio(status)', function (data) {
                var block = $('#offline-tips-block');
                if (data.value === 'offline') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(analytics)', function (data) {
                var block = $('#analytics-script-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            upload.render({
                elem: '#upload-logo',
                url: '/admin/upload/icon/img',
                accept: 'images',
                acceptMime: 'image/*',
                before: function () {
                    layer.load();
                },
                done: function (res) {
                    $('input[name=logo]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function () {
                    layer.msg(helper.locale('upload_failed'), {icon: 2});
                }
            });

            upload.render({
                elem: '#upload-favicon',
                url: '/admin/upload/icon/img',
                accept: 'images',
                acceptMime: 'image/*',
                before: function () {
                    layer.load();
                },
                done: function (res) {
                    $('input[name=favicon]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function () {
                    layer.msg(helper.locale('upload_failed'), {icon: 2});
                }
            });

        });

    </script>

{% endblock %}
