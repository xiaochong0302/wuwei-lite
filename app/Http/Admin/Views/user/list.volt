{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set add_url = url({'for':'admin.user.add'}) %}
    {% set search_url = url({'for':'admin.user.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('user_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_user') }}
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_user') }}
            </a>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('user_avatar') }}</th>
            <th>{{ locale._('id') }}</th>
            <th>{{ locale._('user_name') }}</th>
            <th>{{ locale._('email') }}</th>
            <th>{{ locale._('vip') }}</th>
            <th>{{ locale._('locked') }}</th>
            <th>{{ locale._('active_time') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'admin.user.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.user.edit','id':item.id}) %}
            <tr>
                <td><img class="kg-avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}"></td>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                <td><a href="mailto:{{ item.account.email }}">{{ item.account.email }}</a></td>
                {% if item.vip == 1 %}
                    <td><a class="layui-badge layui-bg-red" href="javascript:" title="{{ locale._('expiry_time') }}：{{ date('Y-m-d H:i',item.vip_expiry_time) }}">{{ locale._('yes') }}</a></td>
                {% else %}
                    <td>{{ locale._('no') }}</td>
                {% endif %}
                {% if item.locked == 1 %}
                    <td><a class="layui-badge layui-bg-red" href="javascript:" title="{{ locale._('expiry_time') }}：{{ date('Y-m-d H:i',item.lock_expiry_time) }}">{{ locale._('yes') }}</a></td>
                {% else %}
                    <td>{{ locale._('no') }}</td>
                {% endif %}
                <td>{{ item.active_time > 0 ? date('Y-m-d H:i',item.active_time) : 'N/A' }}</td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ show_url }}">{{ locale._('view') }}</a></li>
                            {% if item.admin_role.id != 1 %}
                                <li><a href="{{ edit_url }}">{{ locale._('edit') }}</a></li>
                            {% endif %}
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}
