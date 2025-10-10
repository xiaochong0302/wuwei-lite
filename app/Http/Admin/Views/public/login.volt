{% extends 'templates/main.volt' %}

{% block content %}

    <div class="kg-login-wrap">
        <div class="layui-card">
            <div class="layui-card-header">{{ locale._('admin_login') }}</div>
            <div class="layui-card-body">
                <form class="layui-form kg-login-form" method="POST" action="{{ url({'for':'admin.login'}) }}">
                    <div class="layui-form-item">
                        <div class="layui-input-wrap">
                            <div class="layui-input-prefix"><i class="layui-icon layui-icon-email"></i></div>
                            <input class="layui-input" type="text" name="email" autocomplete="off" placeholder="{{ locale._('email') }}" lay-verify="email|required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-wrap">
                            <div class="layui-input-prefix"><i class="layui-icon layui-icon-password"></i></div>
                            <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="{{ locale._('password') }}" lay-affix="eye" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button id="submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">{{ locale._('login') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="kg-login-copyright">
        Powered by <a href="{{ app_info.link }}" title="{{ app_info.name }}">{{ app_info.alias }} {{ app_info.version }}</a>
    </div>

{% endblock %}

{% block inline_css %}

    <style>
        body {
            background: #16a085;
        }
    </style>

{% endblock %}

{% block inline_js %}

    <script>
        if (window !== top) {
            top.location.href = window.location.href;
        }
    </script>

{% endblock %}
