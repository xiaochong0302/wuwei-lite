{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('my_refunds') }}</span>
                </div>
                {% if pager.total_items > 0 %}
                    <table class="layui-table kg-table" lay-size="lg">
                        <colgroup>
                            <col width="40%">
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>{{ locale._('product') }}</th>
                            <th>{{ locale._('amount') }}</th>
                            <th>{{ locale._('status') }}</th>
                            <th>{{ locale._('create_date') }}</th>
                            <th>{{ locale._('actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set refund_info_url = url({'for':'home.refund.info'},{'sn':item.sn}) %}
                            <tr>
                                <td>{{ item.subject }}</td>
                                <td class="price">{{ item.amount|human_price }}</td>
                                <td>{{ refund_status(item.status) }}</td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-sm btn-refund-info" data-url="{{ refund_info_url }}">
                                        {{ locale._('details') }}
                                    </button>
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
