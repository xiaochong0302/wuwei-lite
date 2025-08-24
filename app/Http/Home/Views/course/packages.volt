<div class="package-list">
    {% for package in packages %}
        {% set order_url = url({'for':'home.order.confirm'},{'item_id':package.id,'item_type':2}) %}
        <div class="package-item">
            <div class="package-info">
                <div class="title" title="{{ package.title }}">{{ package.title }}</div>
                <div class="origin-price">
                    <span>{{ locale._('package_courses_x',['x':package.course_count]) }}</span>
                    <span><i>{{ package.origin_price|human_price }}</i></span>
                </div>
                <div class="price">
                    <span>{{ locale._('regular_price') }} <i>{{ package.regular_price|human_price }}</i></span>
                </div>
                <div class="price">
                    <span>{{ locale._('vip_price') }} <i>{{ package.vip_price|human_price }}</i></span>
                </div>
                <div class="order">
                    <button class="layui-btn layui-btn-sm layui-btn-danger btn-buy" data-url="{{ order_url }}">{{ locale._('buy_now') }}</button>
                </div>
            </div>
            <div class="package-course-list">
                {% for course in package.courses %}
                    {% set course_url = url({'for':'home.course.show','id':course.id,'slug':course.slug}) %}
                    <div class="package-course-card" title="{{ course.title }}">
                        <div class="cover"><img src="{{ course.cover }}!cover_270" alt="{{ course.title }}"></div>
                        <div class="title"><a href="{{ course_url }}" title="{{ course.title }}" target="_blank">{{ course.title }}</a></div>
                    </div>
                    {% if loop.first %}
                        <div class="separator"><i class="layui-icon layui-icon-add-1"></i></div>
                    {% endif %}
                {% endfor %}
            </div>
        </div>
    {% endfor %}
</div>
