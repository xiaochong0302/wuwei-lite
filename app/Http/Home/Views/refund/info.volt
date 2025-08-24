{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/refund') }}

    {% set cancel_url = url({'for':'home.refund.cancel'}) %}

    <table class="layui-table kg-table" lay-size="lg">
        <tr>
            <td colspan="2">
                <label class="key colon">{{ locale._('sn') }}</label><span class="value">{{ refund.sn }}</span>
                <label class="key colon">{{ locale._('amount') }}</label><span class="value price">{{ refund.amount|human_price }}</span>
                <label class="key colon">{{ locale._('status') }}</label><span class="value">{{ refund_status(refund.status) }}</span>
            </td>
        </tr>
        <tr>
            <td>{{ refund.subject }}</td>
            <td>{{ status_history(refund.status_history) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if refund.me.allow_cancel == 1 %}
            <button class="layui-btn layui-bg-red btn-cancel" data-sn="{{ refund.sn }}" data-url="{{ cancel_url }}">{{ locale._('cancel_refund') }}</button>
        {% endif %}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/refund.info.js') }}

{% endblock %}
