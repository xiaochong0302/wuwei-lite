{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set edit_url = url({'for':'admin.user.edit','id':user.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{ locale._('user_details') }}</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('profile_info') }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('user/basic') }}
            </div>
        </div>
    </div>

{% endblock %}
