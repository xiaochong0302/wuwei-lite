{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">{{ locale._('page_home') }}</a>
        <a><cite>{{ locale._('payment') }}</cite></a>
    </div>

    <div class="payment wrap">
        <div class="meta-list">
            <div class="meta">
                <label class="key colon">{{ locale._('product') }}</label>
                <span class="value">{{ order.subject }}</span>
            </div>
            <div class="meta">
                <label class="key colon">{{ locale._('amount') }}</label>
                <span class="amount">{{ '%0.2f'|format(order.amount) }}</span>
                <span class="currency">{{ site_info.currency }}</span>
            </div>
        </div>
        {% if order.create_time + 3600 > time() %}
        <div class="countdown">
            <div class="icon"><i class="layui-icon layui-icon-time"></i></div>
            <div class="timer"></div>
            <div class="tips">{{ locale._('order_auto_close_tips') }}</div>
        </div>
        <div class="channel">
            {% if pay_provider.paypal.enabled == 1 %}
                <button class="layui-btn layui-bg-orange btn-pay" data-channel="1">{{ locale._('pay_with_paypal') }}</button>
            {% endif %}
            {% if pay_provider.stripe.enabled == 1 %}
                <button class="layui-btn layui-bg-blue btn-pay" data-channel="2">{{ locale._('pay_with_stripe') }}</button>
            {% endif %}
        </div>
        {% else %}
            <div class="countdown">
                <div class="icon"><i class="layui-icon layui-icon-time"></i></div>
            </div>
            <div class="expired-tips">{{ locale._('order_expired_tips') }}</div>
        {% endif %}
    </div>

    <div class="layui-hide">
        <input type="hidden" name="order.sn" value="{{ order.sn }}">
        <input type="hidden" name="countdown.end_time" value="{{ order.create_time + 3600 }}">
        <input type="hidden" name="countdown.server_time" value="{{ time() }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/order.pay.js') }}

{% endblock %}
