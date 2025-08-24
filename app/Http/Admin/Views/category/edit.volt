{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.category.update','id':category.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_category') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('category_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ category.name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('sort_order') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ category.priority }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('published') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if category.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if category.published == 0 %}checked="checked"{% endif %}>
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
