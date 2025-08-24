{% if course.me.logged == 0 %}
    {% set login_url = url({'for':'home.account.login'}) %}
    <div class="sidebar wrap">
        <a class="layui-btn layui-btn-fluid layui-bg-green" href="{{ login_url }}">{{ locale._('login') }}</a>
    </div>
{% elseif course.me.allow_study == 1 %}
    {% set study_url = url({'for':'home.course.study','id':course.id}) %}
    {% set study_text = course.me.progress == 0 ? locale._('start_learning') : locale._('continue_learning') %}
    <div class="sidebar wrap">
        <button class="layui-btn layui-btn-fluid layui-bg-blue btn-study" data-url="{{ study_url }}">{{ study_text }}</button>
    </div>
{% elseif course.me.allow_order == 1 %}
    {% set order_url = url({'for':'home.order.confirm'},{'item_id':course.id,'item_type':1}) %}
    <div class="sidebar wrap">
        <button class="layui-btn layui-btn-fluid layui-btn-danger btn-buy" data-url="{{ order_url }}">{{ locale._('buy_now') }}</button>
    </div>
{% endif %}
