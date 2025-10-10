{% extends 'templates/main.volt' %}

{% block content %}

    <div class="vip-header">{{ locale._('vip_privileges') }}</div>

    <div class="vip-priv-list wrap">
        <button class="layui-btn layui-bg-blue">{{ locale._('vip_discount_courses') }}</button>
        <button class="layui-btn layui-bg-red">{{ locale._('vip_free_courses') }}</button>
        <button class="layui-btn layui-bg-black">{{ locale._('vip_hd_videos') }}</button>
        <button class="layui-btn layui-bg-green">{{ locale._('vip_ad_free') }}</button>
        <button class="layui-btn layui-bg-purple">{{ locale._('vip_priority_support') }}</button>
        <button class="layui-btn layui-bg-orange">{{ locale._('vip_special_badge') }}</button>
    </div>

    <div class="vip-header">{{ locale._('vip_subscription') }}</div>

    <div class="vip-plan-list">
        <div class="layui-row layui-col-space20">
            {% for plan in plans %}
                {% set order_url = url({'for':'home.order.confirm'},{'item_id':plan.id,'item_type':3}) %}
                <div class="layui-col-md3">
                    <div class="vip-plan-card">
                        <div class="title">{{ locale._('month_x',['x':plan.expiry]) }}</div>
                        <div class="price">{{ plan.price|human_price }}</div>
                        <div class="order">
                            <button class="layui-btn layui-btn-sm layui-bg-red btn-order" data-url="{{ order_url }}">{{ locale._('subscribe_now') }}</button>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>

    {% set free_courses_url = url({'for':'home.vip.courses'},{'type':'free'}) %}
    {% set discount_courses_url = url({'for':'home.vip.courses'},{'type':'discount'}) %}
    {% set users_url = url({'for':'home.vip.users'}) %}

    <div class="vip-tab-wrap">
        <div class="layui-tabs vip-tab">
            <ul class="layui-tabs-header">
                <li class="layui-this">{{ locale._('vip_discount_courses') }}</li>
                <li>{{ locale._('vip_free_courses') }}</li>
                <li>{{ locale._('vip_recent_users') }}</li>
            </ul>
            <div class="layui-tabs-body">
                <div class="layui-tabs-item layui-show" id="tab-discount-courses" data-url="{{ discount_courses_url }}"></div>
                <div class="layui-tabs-item" id="tab-free-courses" data-url="{{ free_courses_url }}"></div>
                <div class="layui-tabs-item" id="tab-users" data-url="{{ users_url }}"></div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/vip.index.js') }}

{% endblock %}
