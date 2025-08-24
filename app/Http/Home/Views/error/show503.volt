{% extends 'templates/error.volt' %}

{% block content %}

    {% set name = 'koogua' %}

    <div class="layui-fluid">
        <div class="kg-tips">
            <i class="layui-icon layui-icon-face-surprised"></i>
            <div class="message">{{ locale._('error_503_tips') }}</div>
            <div class="layui-text">
                <h1>
                    <span class="layui-anim layui-anim-loop">5</span>
                    <span class="layui-anim layui-anim-loop">0</span>
                    <span class="layui-anim layui-anim-loop">3</span>
                </h1>
            </div>
        </div>
    </div>

{% endblock %}
