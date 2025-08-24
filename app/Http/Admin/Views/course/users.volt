{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course_user') }}

    {% set search_url = url({'for':'admin.course.search_user','id':course.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back"><i class="layui-icon layui-icon-return"></i>{{ locale._('back') }}</a>
                <a><cite>{{ course.title }}</cite></a>
                <a><cite>{{ locale._('course_users') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('cu_search_user') }}
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
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('cu_user') }}</th>
            <th>{{ locale._('cu_progress') }}</th>
            <th>{{ locale._('cu_duration') }}</th>
            <th>{{ locale._('cu_join_type') }}</th>
            <th>{{ locale._('expiry_time') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set learnings_url = url({'for':'admin.course.learnings','id':item.course_id},{'user_id':item.user_id}) %}
            {% set user_url = url({'for':'admin.user.show','id':item.user_id}) %}
            <tr>
                <td><a href="{{ user_url }}">{{ item.user.name }}</a> ({{ item.user.id }})</td>
                <td>{{ item.progress }}%</td>
                <td>{{ item.duration|duration }}</td>
                <td>{{ join_type(item.join_type) }}</td>
                <td>{{ item.expiry_time > 0 ? date('Y-m-d H:i',item.expiry_time) : 'N/A' }}</td>
                <td class="kg-center">
                    <a class="layui-btn layui-btn-sm kg-learnings" href="javascript:" data-url="{{ learnings_url }}">{{ locale._('cu_history') }}</a>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            $('.kg-learnings').on('click', function () {
                var url = $(this).data('url');
                var title = $(this).text();
                layer.open({
                    type: 2,
                    title: title,
                    resize: false,
                    area: ['80%', '80%'],
                    content: [url],
                });
            });

        });

    </script>

{% endblock %}
