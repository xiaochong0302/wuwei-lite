{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.audit.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('search_audit') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('audit_user_id') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('audit_user_ip') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_ip" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('audit_req_route') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="req_route" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('audit_req_path') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="req_path" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item" id="time-range">
            <label class="layui-form-label">{{ locale._('create_time') }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="start-time" type="text" name="create_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="end-time" type="text" name="create_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">{{ locale._('submit') }}</button>
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
                elem: '#time-range',
                type: 'datetime',
                range: ['#start-time', '#end-time'],
            });

        });

    </script>

{% endblock %}
