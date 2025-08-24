{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

    {% if pager.total_items > 0 %}
        <table class="layui-table kg-table">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>{{ locale._('cu_lesson') }}</th>
                <th>{{ locale._('client_type') }}</th>
                <th>{{ locale._('client_ip') }}</th>
                <th>{{ locale._('cu_duration') }}</th>
                <th>{{ locale._('create_time') }}</th>
            </tr>
            </thead>
            <tbody>
            {% for item in pager.items %}
                <tr>
                    <td>{{ item.chapter.title }}</td>
                    <td>{{ client_type(item.client_type) }}</td>
                    <td>{{ item.client_ip }}</td>
                    <td>{{ item.duration|duration }}</td>
                    <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
        {{ partial('partials/pager') }}
    {% endif %}

{% endblock %}
