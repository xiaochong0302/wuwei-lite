{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 1 %}
            {{ locale._('role_type_system') }}
        {% elseif value == 2 %}
            {{ locale._('role_type_custom') }}
        {% endif %}
    {%- endmacro %}

    {% set add_url = url({'for':'admin.role.add'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('role_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_role') }}
            </a>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('id') }}</th>
            <th>{{ locale._('role_name') }}</th>
            <th>{{ locale._('role_type') }}</th>
            <th>{{ locale._('role_users') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in roles %}
            {% set edit_url = url({'for':'admin.role.edit','id':item.id}) %}
            {% set delete_url = url({'for':'admin.role.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.role.restore','id':item.id}) %}
            {% set users_url = url({'for':'admin.user.list'},{'admin_role':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                {% if item.id == 1 %}
                    <td><a href="javascript:" title="{{ item.summary }}">{{ item.name }}</a></td>
                {% else %}
                    <td><a href="{{ edit_url }}" title="{{ item.summary }}">{{ item.name }}</a></td>
                {% endif %}
                <td>{{ type_info(item.type) }}</td>
                <td>
                    <a href="{{ users_url }}">
                        <span class="layui-badge layui-bg-green">{{ item.user_count }}</span>
                    </a>
                </td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.id == 1 %}
                                <li><a href="javascript:">{{ locale._('edit') }}</a></li>
                                <li><a href="javascript:">{{ locale._('delete') }}</a></li>
                            {% else %}
                                <li><a href="{{ edit_url }}">{{ locale._('edit') }}</a></li>
                                {% if item.deleted == 0 %}
                                    <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">{{ locale._('delete') }}</a></li>
                                {% else %}
                                    <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">{{ locale._('restore') }}</a></li>
                                {% endif %}
                            {% endif %}
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}
