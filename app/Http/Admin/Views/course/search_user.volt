{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.course.users','id':course.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('cu_search_user') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('cu_course') }}</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ course.title }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('cu_account') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="{{ locale._('account_search_tips') }}">
            </div>
        </div>
        <div class="layui-form-item" id="expiry-time-range">
            <label class="layui-form-label">{{ locale._('expiry_time') }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="expiry-start-time" type="text" name="expiry_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="expiry-end-time" type="text" name="expiry_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('cu_join_type') }}</label>
            <div class="layui-input-block">
                {% for value,title in source_types %}
                    <input type="checkbox" name="source_type[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="search">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: '#expiry-time-range',
                type: 'datetime',
                range: ['#expiry-start-time', '#expiry-end-time'],
            });

        });

    </script>

{% endblock %}
