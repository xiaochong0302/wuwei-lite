{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.user.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('add_user' )}}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('email' )}}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="email" lay-verify="email">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('password' )}}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="password" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('admin_role' )}}</label>
            <div class="layui-input-block">
                <input type="radio" name="admin_role" value="0" title="N/A" checked="checked">
                {% if auth_user.admin_role == 1 %}
                    {% for role in admin_roles %}
                        {% if role.id > 1 %}
                            <input type="radio" name="admin_role" value="{{ role.id }}" title="{{ role.name }}">
                        {% endif %}
                    {% endfor %}
                {% endif %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('edu_role' )}}</label>
            <div class="layui-input-block">
                <input type="radio" name="edu_role" value="1" title="{{ locale._('edu_role_student') }}" checked="checked">
                <input type="radio" name="edu_role" value="2" title="{{ locale._('edu_role_teacher') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit' )}}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back' )}}</button>
            </div>
        </div>
    </form>

{% endblock %}
