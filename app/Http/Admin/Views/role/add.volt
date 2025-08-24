{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.role.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('add_role') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('role_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('role_summary') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="summary">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
            </div>
        </div>
    </form>

{% endblock %}
