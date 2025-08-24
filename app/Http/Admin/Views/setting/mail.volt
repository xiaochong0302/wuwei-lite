{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('smtp_configuration') }}</li>
            <li>{{ locale._('notification_preferences') }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/mail_configuration') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/mail_notification') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(smtp_auth_enabled)', function (data) {
                var block = $('#smtp-auth-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}
