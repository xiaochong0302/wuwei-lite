{% extends 'templates/main.volt' %}

{% block content %}

    {% set back_url = url({'for':'admin.category.list'}) %}
    {% set add_url = url({'for':'admin.category.add'},{'parent_id':parent.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                {% if parent.id > 0 %}
                    <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>{{ locale._('back') }}</a>
                    <a><cite>{{ parent.name }}</cite></a>
                {% endif %}
                <a><cite>{{ locale._('category_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            {% if parent.level < 2 %}
                <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                    <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_category') }}
                </a>
            {% endif %}
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col>
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
            <th>{{ locale._('category_name') }}</th>
            <th>{{ locale._('category_level') }}</th>
            <th>{{ locale._('child_nodes') }}</th>
            <th>{{ locale._('sort_order') }}</th>
            <th>{{ locale._('published') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in categories %}
            {% set child_url = url({'for':'admin.category.list'},{'parent_id':item.id}) %}
            {% set edit_url = url({'for':'admin.category.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.category.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.category.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.category.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                {% if item.level == 1 %}
                    <td><a href="{{ child_url }}"><i class="layui-icon layui-icon-add-circle"></i> {{ item.name }}</a></td>
                {% else %}
                    <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                {% endif %}
                <td>{{ item.level }}</td>
                <td>{{ item.child_count }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="{{ locale._('sort_order_tips') }}" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="{{ locale._('switch_text') }}" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
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

{% endblock %}
