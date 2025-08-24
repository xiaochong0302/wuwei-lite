{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'admin.setting.payment'}) %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('paypal') }}</li>
            <li>{{ locale._('stripe') }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/payment_paypal') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/payment_stripe') }}
            </div>
        </div>
    </div>

{% endblock %}
