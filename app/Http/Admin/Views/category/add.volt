{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.category.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('add_category') }}</legend>
        </fieldset>
        {% if parent.id > 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('category_parent') }}</label>
                <div class="layui-input-block">
                    <select name="parent_id" lay-verify="required">
                        <option value="">{{ locale._('select') }}</option>
                        {% for category in top_categories %}
                            {% set selected = category.id == parent.id ? 'selected="selected"' : '' %}
                            <option value="{{ category.id }}" {{ selected }}>{{ category.name }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('category_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('sort_order') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="10" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                <input type="hidden" name="parent_id" value="{{ parent.id }}">
            </div>
        </div>
    </form>

{% endblock %}
