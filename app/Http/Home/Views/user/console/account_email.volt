{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('change_email') }}</span>
                </div>
                <form class="layui-form security-form" method="POST" action="{{ url({'for':'home.account.update_email'}) }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('login_pwd') }}</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="login_password" autocomplete="off" placeholder="{{ locale._('login_pwd_tips') }}" lay-affix="eye" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('new_email') }}</label>
                        <div class="layui-input-block">
                            <input id="cv-email" class="layui-input" type="text" name="email" autocomplete="off" placeholder="{{ locale._('new_email_tips') }}" lay-verify="email">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('verify_code') }}</label>
                        <div class="layui-input-inline verify-input-inline">
                            <input class="layui-input" type="text" name="verify_code" autocomplete="off" lay-verify="required">
                        </div>
                        <div class="layui-input-inline verify-btn-inline">
                            <button id="cv-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">{{ locale._('get_verify_code') }}</button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button id="cv-submit-btn" class="layui-btn layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                            <button class="layui-btn layui-btn-primary" type="reset">{{ locale._('reset') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}
