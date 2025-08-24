{% extends 'templates/main.volt' %}

{% block content %}

    <table class="kg-table layui-table">
        <tr>
            <td>{{ locale._('refund_status') }}</td>
            <td>{{ locale._('create_time') }}</td>
        </tr>
        {% for item in status_history %}
            <tr>
                <td>{{ refund_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            </tr>
        {% endfor %}
    </table>

{% endblock %}
