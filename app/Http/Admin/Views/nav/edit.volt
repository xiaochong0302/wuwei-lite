{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.nav.update','id':nav.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_nav') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('nav_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ nav.name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('nav_link') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="url" value="{{ nav.url }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('sort_order') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ nav.priority }}" lay-verify="number">
            </div>
        </div>
        {% if nav.parent_id == 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('nav_position') }}</label>
                <div class="layui-input-block">
                    <input type="radio" name="position" value="1" title="{{ locale._('nav_pos_top') }}" {% if nav.position == 1 %}checked="checked"{% endif %}>
                    <input type="radio" name="position" value="2" title="{{ locale._('nav_pos_bottom') }}" {% if nav.position == 2 %}checked="checked"{% endif %}>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('nav_target') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="target" value="_blank" title="{{ locale._('nav_target_blank') }}" {% if nav.target == '_blank' %}checked="checked"{% endif %}>
                <input type="radio" name="target" value="_self" title="{{ locale._('nav_target_self') }}" {% if nav.target == '_self' %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('published') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if nav.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if nav.published == 0 %}checked="checked"{% endif %}>
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
