{% extends 'templates/main.volt' %}

{% block content %}

    {% set add_url = url({'for':'admin.course.add'}) %}
    {% set search_url = url({'for':'admin.course.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('course_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_course') }}
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_course') }}
            </a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
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
            <th>{{ locale._('id') }}</th>
            <th>{{ locale._('course_category') }}</th>
            <th>{{ locale._('course_title') }}</th>
            <th>{{ locale._('regular_price') }}</th>
            <th>{{ locale._('vip_price') }}</th>
            <th>{{ locale._('featured') }}</th>
            <th>{{ locale._('published') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set course_url = url({'for':'home.course.show','id':item.id,'slug':item.slug}) %}
            {% set edit_url = url({'for':'admin.course.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.course.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.course.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.course.restore','id':item.id}) %}
            {% set modules_url = url({'for':'admin.course.modules','id':item.id}) %}
            {% set users_url = url({'for':'admin.course.users','id':item.id}) %}
            {% set reviews_url = url({'for':'admin.review.list'},{'course_id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                {% if item.category.id is defined %}
                    <td>{{ item.category.name }}</td>
                {% else %}
                    <td>N/A</td>
                {% endif %}
                <td><a href="{{ modules_url }}">{{ item.title }}</a></td>
                <td>{{ item.regular_price|human_price }}</td>
                <td>{{ item.vip_price|human_price }}</td>
                <td><input type="checkbox" name="featured" value="1" lay-skin="switch" lay-filter="go" lay-text="{{ locale._('switch_text') }}" data-url="{{ update_url }}"
                           {% if item.featured == 1 %}checked="checked"{% endif %}></td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-filter="go" lay-text="{{ locale._('switch_text') }}" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ course_url }}" target="_blank">{{ locale._('view') }}</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">{{ locale._('edit') }}</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">{{ locale._('delete') }}</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">{{ locale._('restore') }}</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ modules_url }}">{{ locale._('course_modules') }}</a></li>
                            <li><a href="{{ reviews_url }}">{{ locale._('course_reviews') }}</a></li>
                            <li><a href="{{ users_url }}">{{ locale._('course_users') }}</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}
