{% extends 'templates/main.volt' %}

{% block content %}

    {% set add_url = url({'for':'admin.page.add'}) %}
    {% set search_url = url({'for':'admin.page.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('page_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_page') }}
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_page') }}
            </a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('id') }}</th>
            <th>{{ locale._('page_title') }}</th>
            <th>{{ locale._('page_view_count') }}</th>
            <th>{{ locale._('create_time') }}</th>
            <th>{{ locale._('published') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set page_url = url({'for':'home.page.show','id':item.id,'slug':item.slug}) %}
            {% set edit_url = url({'for':'admin.page.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.page.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.page.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.page.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}">{{ item.title }}</a></td>
                <td>{{ item.view_count }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-filter="go" lay-text="{{ locale._('switch_text') }}" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}>
                </td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ page_url }}" target="_blank">{{ locale._('view') }}</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">{{ locale._('edit') }}</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">{{ locale._('delete') }}</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">{{ locale._('restore') }}</a></li>
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
