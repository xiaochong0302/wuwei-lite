{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.user.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('search_user') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('user_account') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="{{ locale._('account_search_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('user_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" placeholder="{{ locale._('wildcard_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item" id="create-time-range">
            <label class="layui-form-label">{{ locale._('create_time') }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="create-start-time" type="text" name="create_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid"> -</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="create-end-time" type="text" name="create_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item" id="active-time-range">
            <label class="layui-form-label">{{ locale._('active_time') }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="active-start-time" type="text" name="active_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid"> -</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="active-end-time" type="text" name="active_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('admin_role') }}</label>
            <div class="layui-input-block">
                {% for item in admin_roles %}
                    <input type="checkbox" name="admin_role[]" value="{{ item.id }}" title="{{ item.name }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('edu_role') }}</label>
            <div class="layui-input-block">
                {% for value,title in edu_roles %}
                    <input type="checkbox" name="edu_role[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('vip') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="vip" value="1" title="{{ locale._('yes') }}">
                <input type="radio" name="vip" value="0" title="{{ locale._('no') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('locked') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="locked" value="1" title="{{ locale._('yes') }}">
                <input type="radio" name="locked" value="0" title="{{ locale._('no') }}">
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
                elem: '#create-time-range',
                type: 'datetime',
                range: ['#create-start-time', '#create-end-time'],
            });

            laydate.render({
                elem: '#active-time-range',
                type: 'datetime',
                range: ['#active-start-time', '#active-end-time'],
            });

        });

    </script>

{% endblock %}
