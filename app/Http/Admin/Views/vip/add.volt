{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.vip.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('add_vip_plan') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('vip_plan_duration') }}</label>
            <div class="layui-input-block">
                <select name="expiry" lay-verify="required">
                    <option value="">{{ locale._('select') }}</option>
                    {% for value in 1..60 %}
                        <option value="{{ value }}">{{ locale._('vip_plan_month_x', ['x':value]) }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('vip_plan_price') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="price" lay-verify="number">
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
