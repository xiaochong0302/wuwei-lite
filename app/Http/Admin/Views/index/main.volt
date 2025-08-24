{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8">
                {{ partial('index/main_latest_orders') }}
                {{ partial('index/main_latest_users') }}
            </div>
            <div class="layui-col-md4">
                {{ partial('index/main_global_stat') }}
                {{ partial('index/main_today_stat') }}
                {{ partial('index/main_system_usage') }}
                {{ partial('index/main_help_center') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_css %}

    <style>
        .kg-body {
            padding: 15px 0;
            background: #f2f2f2;
        }
    </style>

{% endblock %}
