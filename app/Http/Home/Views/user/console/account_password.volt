{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('change_pwd') }}</span>
                </div>
                <form class="layui-form security-form" method="POST" action="{{ url({'for':'home.account.update_pwd'}) }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('old_pwd') }}</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="old_password" autocomplete="off" lay-affix="eye" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('new_pwd') }}</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="new_password" autocomplete="off" lay-affix="eye" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ locale._('confirm_pwd') }}</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="confirm_password" autocomplete="off" lay-affix="eye" lay-verify="required">
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
