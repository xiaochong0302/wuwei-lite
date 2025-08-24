{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'home.account.do_register'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">{{ locale._('home') }}</a>
        <a><cite>{{ locale._('register_account') }}</cite></a>
    </div>

    <div class="login-wrap wrap">
        {% if site_info.allow_register == 1 %}
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
                        <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="{{ locale._('password') }}" lay-affix="eye" lay-verify="required">
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
                        <button id="cv-submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">{{ locale._('register_account') }}</button>
                        <input id="cv-captcha-ticket" type="hidden" name="captcha[ticket]">
                        <input id="cv-captcha-rand" type="hidden" name="captcha[rand]">
                        <input type="hidden" name="return_url" value="{{ return_url }}">
                    </div>
                </div>
            </form>
            <div class="link">
                <a class="login-link" href="{{ url({'for':'home.account.login'}) }}">{{ locale._('login_account') }}</a>
                <span class="separator">·</span>
                <a class="forget-link" href="{{ url({'for':'home.account.forget'}) }}">{{ locale._('forget_pwd') }}</a>
            </div>
        {% else %}
            <div class="register-disabled-tips">
                <div class="icon"><i class="layui-icon layui-icon-lock"></i></div>
                <div class="msg">{{ locale._('register_disabled_tips') }}</div>
            </div>
        {% endif %}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}
