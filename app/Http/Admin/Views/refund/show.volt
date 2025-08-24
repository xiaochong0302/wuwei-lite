{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/order') }}

    {% set refund_sh_url = url({'for':'admin.refund.status_history','id':refund.id}) %}
    {% set refund_review_url = url({'for':'admin.refund.review','id':refund.id}) %}

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
        </tr>
        <tr>
            <td>{{ refund.sn }}</td>
            <td>{{ refund.amount|human_price }}</td>
            <td>
                <p class="layui-elip" title="{{ refund.apply_note }}">{{ refund.apply_note }}</p>
            </td>
            <td><a class="kg-status-history" href="javascript:" title="{{ locale._('status_timeline') }}" data-url="{{ refund_sh_url }}">{{ refund_status(refund.status) }}</a></td>
            <td>{{ date('Y-m-d H:i',refund.create_time) }}</td>
        </tr>
    </table>
    <br>

    <div class="kg-center">
        <button class="layui-btn layui-btn-primary kg-back">{{ locale._('back') }}</button>
    </div>

    {% if refund.status == 1 %}
        <form class="layui-form kg-form" method="POST" action="{{ refund_review_url }}">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>{{ locale._('refund_review') }}</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('refund_review_status') }}</label>
                <div class="layui-input-block">
                    <input type="radio" name="review_status" value="3" title="{{ locale._('status_approved') }}" checked="checked">
                    <input type="radio" name="review_status" value="4" title="{{ locale._('status_rejected') }}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('refund_review_note') }}</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="review_note" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                    <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                </div>
            </div>
        </form>
    {% endif %}

    {{ partial('order/order_info') }}

    {{ partial('order/user_info') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/status-history.js') }}

{% endblock %}
