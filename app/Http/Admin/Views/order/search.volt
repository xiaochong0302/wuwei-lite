{% extends 'templates/main.volt' %}

{% block content %}

    {% set target = request.get('target','string','search') %}
    {% set count = request.get('count','int',-1) %}

    {% if target == 'search' %}
        {% set action_url = url({'for':'admin.order.list'}) %}
        {% set title = locale._('search_order') %}
    {% else %}
        {% set action_url = url({'for':'admin.order.export'}) %}
        {% set title = locale._('export_order') %}
    {% endif %}

    <form class="layui-form kg-form" method="GET" action="{{ action_url }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ title }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('order_no') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="order_id" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('order_customer') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" placeholder="{{ locale._('account_search_tips') }}">
            </div>
        </div>
        <div class="layui-form-item" id="time-range">
            <label class="layui-form-label">{{ locale._('create_time') }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="start-time" type="text" name="create_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="end-time" type="text" name="create_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('order_status') }}</label>
            <div class="layui-input-block">
                {% for value,title in status_types %}
                    <input type="checkbox" name="status[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('product_type') }}</label>
            <div class="layui-input-block">
                {% for value,title in item_types %}
                    <input type="checkbox" name="item_type[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('payment_type') }}</label>
            <div class="layui-input-block">
                {% for value,title in payment_types %}
                    <input type="checkbox" name="payment_type[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="search">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                <input type="hidden" name="target" value="{{ target }}">
                <input type="hidden" name="count" value="{{ count }}">
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/export.search.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: '#time-range',
                type: 'datetime',
                range: ['#start-time', '#end-time'],
            });

        });

    </script>

{% endblock %}
