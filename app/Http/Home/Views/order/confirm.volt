{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro cart_course_card(course) %}
        {% set course_url = url({'for':'home.course.show','id':course.id}) %}
        <div class="cart-item-card">
            <div class="cover">
                <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
            </div>
            <div class="info">
                <p><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>
                    <label class="key colon">{{ locale._('regular_price') }}</label>
                    <span class="value price">{{ course.regular_price|human_price }}</span>
                    <label class="key colon">{{ locale._('vip_price') }}</label>
                    <span class="value price">{{ course.vip_price|human_price }}</span>
                </p>
                <p>
                    <label class="key colon">{{ locale._('study_expiry') }}</label>
                    <span class="value">{{ locale._('month_x',['x':course.study_expiry]) }}</span>
                    {% if course.refund_expiry > 0 %}
                        <label class="key colon">{{ locale._('refund_expiry') }}</label>
                        <span class="value">{{ locale._('day_x',['x':course.refund_expiry]) }}</span>
                    {% else %}
                        <label class="key colon">{{ locale._('refund_expiry') }}</label>
                        <span class="value">{{ locale._('not_supported') }}</span>
                    {% endif %}
                </p>

            </div>
        </div>
    {%- endmacro %}

    {%- macro cart_vip_card(vip) %}
        <div class="cart-item-card">
            <div class="cover">
                <img src="{{ vip.cover }}!cover_270" alt="{{ vip.title }}">
            </div>
            <div class="info">
                <p>{{ locale._('vip_membership') }}</p>
                <p>
                    <label class="key colon">{{ locale._('vip_plan_price') }}</label>
                    <span class="value price">{{ vip.price|human_price }}</span>
                </p>
                <p>
                    <label class="key colon">{{ locale._('vip_plan_duration') }}</label>
                    <span class="value">{{ locale._('month_x',['x':vip.expiry]) }}</span>
                </p>
            </div>
        </div>
    {%- endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">{{ locale._('home') }}</a>
        <a><cite>{{ locale._('confirm_order') }}</cite></a>
    </div>

    <div class="cart-item-list wrap">
        {% if confirm.item_type == 1 %}
            {% set course = confirm.item_info.course %}
            {{ cart_course_card(course) }}
        {% elseif confirm.item_type == 2 %}
            {% set package = confirm.item_info.package %}
            {% for course in package.courses %}
                {{ cart_course_card(course) }}
            {% endfor %}
        {% elseif confirm.item_type == 3 %}
            {% set vip = confirm.item_info.vip %}
            {{ cart_vip_card(vip) }}
        {% endif %}
    </div>

    <div class="cart-stats wrap">
        <div class="item-list">
            <div class="item">
                <label class="key colon">{{ locale._('cart_total') }}</label>
                <span id="total-amount" class="amount">{{ '%0.2f'|format(confirm.total_amount) }}</span>
                <span class="currency">{{ site_info.currency }}</span>
            </div>
            <div class="item">
                <label class="key colon">{{ locale._('cart_discount') }}</label>
                <span id="discount-amount" class="amount">{{ '%0.2f'|format(confirm.total_discount_amount) }}</span>
                <span class="currency">{{ site_info.currency }}</span>
            </div>
            <div class="item">
                <label class="key colon">{{ locale._('cart_pay') }}</label>
                <span id="pay-amount" class="amount pay-amount">{{ '%0.2f'|format(confirm.pay_amount) }}</span>
                <span class="currency">{{ site_info.currency }}</span>
            </div>
        </div>
        <form class="layui-form cart-form" method="POST" action="{{ url({'for':'home.order.create'}) }}">
            <button class="layui-btn layui-btn-danger order-btn" lay-submit="true" lay-filter="go">{{ locale._('submit_order') }}</button>
            <input type="hidden" name="item_id" value="{{ confirm.item_id }}">
            <input type="hidden" name="item_type" value="{{ confirm.item_type }}">
        </form>
    </div>

{% endblock %}
