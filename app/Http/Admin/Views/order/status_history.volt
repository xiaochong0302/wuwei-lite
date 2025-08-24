{% extends 'templates/main.volt' %}

{% block content %}

    <table class="layui-table kg-table">
        <tr>
            <td>{{ locale._('order_status') }}</td>
            <td>{{ locale._('create_time') }}</td>
        </tr>
        {% for item in status_history %}
            <tr>
                <td>{{ order_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            </tr>
        {% endfor %}
    </table>

{% endblock %}
