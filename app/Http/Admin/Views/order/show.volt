{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/order') }}
    {{ partial('order/order_info') }}

    {% if refunds.count() > 0 %}
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('refund_info') }}</legend>
        </fieldset>
        <br>
        <table class="layui-table kg-table">
            <tr>
                <th>{{ locale._('refund_no') }}</th>
                <th>{{ locale._('refund_amount') }}</th>
                <th>{{ locale._('refund_reason') }}</th>
                <th>{{ locale._('refund_status') }}</th>
                <th>{{ locale._('create_time') }}</th>
                <th></th>
            </tr>
            {% for item in refunds %}
                {% set refund_sh_url = url({'for':'admin.refund.status_history','id':item.id}) %}
                {% set refund_show_url = url({'for':'admin.refund.show','id':item.id}) %}
                <tr>
                    <td>{{ item.sn }}</td>
                    <td>{{ item.amount|human_price }}</td>
                    <td><a href="javascript:" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                    <td><a class="kg-status-history" href="javascript:" title="{{ locale._('status_timeline') }}" data-url="{{ refund_sh_url }}">{{ refund_status(item.status) }}</a></td>
                    <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                    <td><a class="layui-btn layui-btn-sm" href="{{ refund_show_url }}">{{ locale._('details') }}</a></td>
                </tr>
            {% endfor %}
        </table>
        <br>
        <div class="kg-center">
            <button class="layui-btn layui-btn-primary kg-back">{{ locale._('back') }}</button>
        </div>
    {% endif %}

    {{ partial('order/user_info') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/status-history.js') }}

{% endblock %}
