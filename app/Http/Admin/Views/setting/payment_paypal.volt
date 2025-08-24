{% set webhook_url = full_url({'for':'home.webhook.paypal'}) %}

<form class="layui-form kg-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('enable') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="{{ locale._('yes') }}" {% if paypal.enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="{{ locale._('no') }}" {% if paypal.enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('paypal_client_id') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="client_id" value="{{ paypal.client_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('paypal_client_secret') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="client_secret" value="{{ paypal.client_secret }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('paypal_webhook_url') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="webhook_url" value="{{ webhook_url }}" readonly="readonly" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('paypal_webhook_id') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="webhook_id" value="{{ paypal.webhook_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('refund_service_fee') }}</label>
        <div class="layui-input-block">
            <select name="service_rate" lay-verify="number">
                {% for value in 0..30 %}
                    {% set selected = (value == paypal.service_rate) ? 'selected="selected"' : '' %}
                    <option value="{{ value }}" {{ selected }}>{{ value }}%</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('reset') }}</button>
            <input type="hidden" name="section" value="payment.paypal">
        </div>
    </div>
</form>
