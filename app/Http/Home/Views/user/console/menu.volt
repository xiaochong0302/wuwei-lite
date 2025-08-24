{%- macro vip_info(user) %}
    {% set vip_url = url({'for':'home.vip.index'}) %}
    {% if user.vip == 1 %}
        <a class="layui-badge layui-bg-orange" title="{{ locale._('vip_expiry') }}：{{ date('Y-m-d',user.vip_expiry_time) }}" href="{{ vip_url }}">{{ locale._('vip') }}</a>
    {% else %}
        <a class="layui-badge layui-bg-gray" title="{{ locale._('get_vip') }}" href="{{ vip_url }}">{{ locale._('vip') }}</a>
    {% endif %}
{%- endmacro %}

<div class="my-profile-card wrap">
    <div class="vip">{{ vip_info(auth_user) }}</div>
    <div class="avatar">
        <img class="my-avatar" src="{{ auth_user.avatar }}" alt="{{ auth_user.name }}">
    </div>
    <div class="name">{{ auth_user.name }}</div>
</div>

<ul class="my-menu wrap">
    <li><a href="{{ url({'for':'home.uc.courses'}) }}">{{ locale._('my_courses') }}</a></li>
    <li><a href="{{ url({'for':'home.uc.favorites'}) }}">{{ locale._('my_favorites') }}</a></li>
    <li class="line"><a href="{{ url({'for':'home.uc.reviews'}) }}">{{ locale._('my_reviews') }}</a></li>
    <li><a href="{{ url({'for':'home.uc.orders'}) }}">{{ locale._('my_orders') }}</a></li>
    <li class="line"><a href="{{ url({'for':'home.uc.refunds'}) }}">{{ locale._('my_refunds') }}</a></li>
    <li><a href="{{ url({'for':'home.uc.profile'}) }}">{{ locale._('my_profile') }}</a></li>
    <li><a href="{{ url({'for':'home.uc.account'}) }}">{{ locale._('my_account') }}</a></li>
</ul>
