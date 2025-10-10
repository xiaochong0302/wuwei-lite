{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'admin.setting.payment'}) %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">{{ locale._('paypal') }}</li>
            <li>{{ locale._('stripe') }}</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/payment_paypal') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/payment_stripe') }}
            </div>
        </div>
    </div>

{% endblock %}
