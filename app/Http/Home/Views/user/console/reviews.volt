{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('my_reviews') }}</span>
                </div>
                {% if pager.total_items > 0 %}
                    <table class="layui-table" lay-size="lg">
                        <colgroup>
                            <col>
                            <col>
                            <col width="25%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>{{ locale._('uc_course') }}</th>
                            <th>{{ locale._('uc_rating') }}</th>
                            <th>{{ locale._('actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'home.course.show','id':item.course.id,'slug':item.course.slug}) %}
                            {% set edit_url = url({'for':'home.review.edit','id':item.id}) %}
                            {% set delete_url = url({'for':'home.review.delete','id':item.id}) %}
                            <tr>
                                <td><a href="{{ course_url }}" title="{{ item.content }}" target="_blank">{{ item.course.title }}</a></td>
                                <td>{{ "%0.1f"|format(item.rating) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-sm btn-edit-review" data-url="{{ edit_url }}">{{ locale._('edit') }}</button>
                                    <button class="layui-btn layui-btn-sm layui-bg-red kg-delete" data-url="{{ delete_url }}">{{ locale._('delete') }}</button>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}
