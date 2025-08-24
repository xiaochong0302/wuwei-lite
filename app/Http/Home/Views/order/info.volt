{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/order') }}

    {% set cancel_url = url({'for':'home.order.cancel'}) %}
    {% set pay_url = url({'for':'home.order.pay'},{'sn':order.sn}) %}
    {% set refund_url = url({'for':'home.refund.confirm'},{'sn':order.sn}) %}

    <table class="layui-table kg-table">
        <tr>
            <td colspan="2">
                <label class="key colon">{{ locale._('sn') }}</label><span class="value">{{ order.sn }}</span>
                <label class="key colon">{{ locale._('amount') }}</label><span class="value price">{{ order.amount|human_price }}</span>
                <label class="key colon">{{ locale._('status') }}</label><span class="value">{{ order_status(order.status) }}</span>
            </td>
        </tr>
        <tr>
            <td>{{ item_info(order) }}</td>
            <td>{{ status_history(order.status_history) }}</td>
        </tr>
    </table>
    <br>
    <div class="center btn-group">
        {% if order.me.allow_pay == 1 %}
            <a class="layui-btn layui-bg-blue" href="{{ pay_url }}" target="_top">{{ locale._('pay_now') }}</a>
        {% endif %}
        {% if order.me.allow_cancel == 1 %}
            <button class="layui-btn layui-bg-red btn-cancel" data-sn="{{ order.sn }}" data-url="{{ cancel_url }}">{{ locale._('cancel_order') }}</button>
        {% endif %}
        {% if order.me.allow_refund == 1 %}
            <a class="layui-btn layui-bg-red" href="{{ refund_url }}">{{ locale._('request_refund') }}</a>
        {% endif %}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/order.info.js') }}

{% endblock %}
