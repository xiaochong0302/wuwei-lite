{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('my_profile') }}</span>
                </div>
                <form class="layui-form security-form" method="POST" action="{{ url({'for':'home.uc.update_profile'}) }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-inline" style="width: 110px;">
                            <img id="img-avatar" class="my-avatar" src="{{ user.avatar }}">
                            <input type="hidden" name="avatar" value="{{ user.avatar }}">
                        </div>
                        <div class="layui-input-inline" style="padding-top:35px;">
                            <button id="change-avatar" class="layui-btn layui-btn-sm" type="button">{{ locale._('upload') }}</button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('user_name') }}</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="name" value="{{ user.name }}" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('user_about') }}</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" name="about" lay-verify="required">{{ user.about }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                            <button class="layui-btn layui-btn-primary" type="reset">{{ locale._('reset') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.profile.js') }}

{% endblock %}
