{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'home.account.reset_pwd'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">{{ locale._('home') }}</a>
        <a><cite>{{ locale._('forget_pwd') }}</cite></a>
    </div>

    <div class="login-wrap wrap">
        <form class="layui-form account-form" method="POST" action="{{ action_url }}">
            <div class="layui-form-item">
                <div class="layui-input-wrap">
                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-email"></i></div>
                    <input id="cv-email" class="layui-input" type="text" name="email" autocomplete="off" placeholder="{{ locale._('email') }}" lay-verify="email">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-wrap">
                    <div class="layui-input-prefix"><i class="layui-icon layui-icon-password"></i></div>
                    <input class="layui-input" type="password" name="new_password" autocomplete="off" placeholder="{{ locale._('new_pwd') }}" lay-affix="eye" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-inline verify-input-inline">
                    <div class="layui-input-wrap">
                        <div class="layui-input-prefix"><i class="layui-icon layui-icon-vercode"></i></div>
                        <input class="layui-input" type="text" name="verify_code" autocomplete="off" placeholder="{{ locale._('verify_code') }}" lay-verify="required">
                    </div>
                </div>
                <div class="layui-input-inline verify-btn-inline">
                    <button id="cv-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">{{ locale._('get_verify_code') }}</button>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button id="cv-submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">{{ locale._('reset_pwd') }}</button>
                </div>
            </div>
        </form>
        <div class="link">
            <a class="login-link" href="{{ url({'for':'home.account.login'}) }}">{{ locale._('login_account') }}</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'home.account.register'}) }}">{{ locale._('register_account') }}</a>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}
