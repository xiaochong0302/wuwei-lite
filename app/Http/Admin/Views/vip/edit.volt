{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.vip.update','id':vip.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_vip_plan') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('vip_plan_duration') }}</label>
            <div class="layui-input-block">
                <select name="expiry" lay-verify="required">
                    <option value="">{{ locale._('select') }}</option>
                    {% for value in 1..60 %}
                        {% set selected = value == vip.expiry ? 'selected="selected"' : '' %}
                        <option value="{{ value }}" {{ selected }}>{{ locale._('vip_plan_month_x', ['x':value]) }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('vip_plan_price') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="price" value="{{ vip.price }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('published') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if vip.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if vip.published == 0 %}checked="checked"{% endif %}>
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
