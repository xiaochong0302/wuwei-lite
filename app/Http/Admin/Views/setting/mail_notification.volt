{% extends 'templates/main.volt' %}

{% block content %}

    {% set notification = mail.notification|json_decode %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.mail'}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('account_login_notify') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="notification[account_login]" value="1" title="{{ locale._('yes') }}" {% if notification.account_login == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notification[account_login]" value="0" title="{{ locale._('no') }}" {% if notification.account_login == "0" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('order_finish_notify') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="notification[order_finish]" value="1" title="{{ locale._('yes') }}" {% if notification.order_finish == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notification[order_finish]" value="0" title="{{ locale._('no') }}" {% if notification.order_finish == "0" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('refund_finish_notify') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="notification[refund_finish]" value="1" title="{{ locale._('yes') }}" {% if notification.refund_finish == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notification[refund_finish]" value="0" title="{{ locale._('no') }}" {% if notification.refund_finish == "0" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('review_remind_notify') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="notification[review_remind]" value="1" title="{{ locale._('yes') }}" {% if notification.review_remind == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notification[review_remind]" value="0" title="{{ locale._('no') }}" {% if notification.review_remind == "0" %}checked="checked"{% endif %}>
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

{% endblock %}
