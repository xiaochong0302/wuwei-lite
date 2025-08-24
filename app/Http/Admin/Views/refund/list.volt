{% extends 'templates/main.volt' %}

{% block content %}

    {% set search_url = url({'for':'admin.refund.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('refund_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_refund') }}
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
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('refund_customer') }}</th>
            <th>{{ locale._('refund_product') }}</th>
            <th>{{ locale._('refund_amount') }}</th>
            <th>{{ locale._('refund_status') }}</th>
            <th>{{ locale._('create_time') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_url = url({'for':'admin.user.show','id':item.owner.id}) %}
            {% set show_url = url({'for':'admin.refund.show','id':item.id}) %}
            <tr>
                <td><a href="{{ user_url }}">{{ item.owner.name }}</a> ({{ item.owner.id }})</td>
                <td>{{ item.order.subject }}</td>
                <td>{{ item.amount|human_price }}</td>
                <td>{{ refund_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                <td class="center">
                    <a class="layui-btn layui-btn-sm" href="{{ show_url }}">{{ locale._('details') }}</a>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}
