{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'home.account.do_login'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">{{ locale._('home') }}</a>
        <a><cite>{{ locale._('login_account') }}</cite></a>
    </div>

    <div class="login-wrap wrap">
        <form class="layui-form account-form" method="POST" action="{{ action_url }}">
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
                    <button class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">{{ locale._('login_account') }}</button>
                    <input type="hidden" name="return_url" value="{{ return_url }}">
                </div>
            </div>
        </form>
        <div class="link">
            <a class="login-link" href="{{ url({'for':'home.account.register'}) }}">{{ locale._('register_account') }}</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'home.account.forget'}) }}">{{ locale._('forget_pwd') }}</a>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        if (window !== top) {
            top.location.href = window.location.href;
        }
    </script>

{% endblock %}
