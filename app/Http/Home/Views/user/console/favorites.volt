{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('my_favorites') }}</span>
                </div>
                {% if pager.total_items > 0 %}
                    <table class="layui-table" lay-size="lg">
                        <colgroup>
                            <col>
                            <col>
                            <col>
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
                            {% set course_url = url({'for':'home.course.show','id':item.id,'slug':item.slug}) %}
                            {% set favorite_url = url({'for':'home.course.favorite','id':item.id}) %}
                            <tr>
                                <td><a href="{{ course_url }}" target="_blank">{{ item.title }}</a></td>
                                <td>{{ "%0.1f"|format(item.rating) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-sm layui-bg-red kg-delete" data-url="{{ favorite_url }}">{{ locale._('delete') }}</button>
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
