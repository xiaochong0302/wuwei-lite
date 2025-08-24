{% extends 'templates/error.volt' %}

{% block content %}

    <div class="layui-fluid">
        <div class="kg-tips">
            <i class="layui-icon layui-icon-face-surprised"></i>
            <div class="message">{{ locale._('error_500_tips') }}</div>
            <div class="layui-text">
                <h1>
                    <span class="layui-anim layui-anim-loop">5</span>
                    <span class="layui-anim layui-anim-loop">0</span>
                    <span class="layui-anim layui-anim-loop">0</span>
                </h1>
            </div>
        </div>
    </div>

{% endblock %}
