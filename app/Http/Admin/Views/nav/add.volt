{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.nav.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('add_nav') }}</legend>
        </fieldset>
        {% if parent_id > 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('nav_parent') }}</label>
                <div class="layui-input-block">
                    <select name="parent_id" lay-verify="required">
                        <option value="">{{ locale._('select') }}</option>
                        {% for nav in top_navs %}
                            {% set selected = category.id == parent_id ? 'selected="selected"' : '' %}
                            <option value="{{ nav.id }}" {{ selected }}>{{ nav.name }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('nav_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('nav_link') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="url" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('sort_order') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="10" lay-verify="number">
            </div>
        </div>
        {% if parent_id == 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('nav_position') }}</label>
                <div class="layui-input-block">
                    <input type="radio" name="position" value="1" title="{{ locale._('nav_pos_top') }}" checked="checked">
                    <input type="radio" name="position" value="2" title="{{ locale._('nav_pos_bottom') }}">
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('nav_target') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="target" value="_blank" title="{{ locale._('nav_target_blank') }}" checked="checked">
                <input type="radio" name="target" value="_self" title="{{ locale._('nav_target_self') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                <input type="hidden" name="parent_id" value="{{ parent_id }}">
            </div>
        </div>
    </form>

{% endblock %}
