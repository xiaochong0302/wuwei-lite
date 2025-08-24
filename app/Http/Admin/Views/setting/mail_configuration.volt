{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.mail'}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('smtp_from_email') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_from_email" value="{{ mail.smtp_from_email }}" lay-verify="email">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('smtp_from_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_from_name" value="{{ mail.smtp_from_name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('smtp_host') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_host" value="{{ mail.smtp_host }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('smtp_port') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_port" value="{{ mail.smtp_port }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('smtp_encrypt') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="smtp_encryption" value="ssl" title="SSL" {% if mail.smtp_encryption == "ssl" %}checked="checked"{% endif %}>
                <input type="radio" name="smtp_encryption" value="tls" title="TLS" {% if mail.smtp_encryption == "tls" %}checked="checked"{% endif %}>
                <input type="radio" name="smtp_encryption" value="" title="No" {% if mail.smtp_encryption == "" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('smtp_auth') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="smtp_auth_enabled" value="1" title="{{ locale._('yes') }}" lay-filter="smtp_auth_enabled" {% if mail.smtp_auth_enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="smtp_auth_enabled" value="0" title="{{ locale._('no') }}" lay-filter="smtp_auth_enabled" {% if mail.smtp_auth_enabled == "0" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="smtp-auth-block">
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('smtp_username') }}</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="smtp_username" value="{{ mail.smtp_username }}" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">{{ locale._('smtp_password') }}</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="smtp_password" value="{{ mail.smtp_password }}" lay-verify="required">
                </div>
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

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.mail'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('email_test') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('email_recipient') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="email" lay-verify="email">
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
