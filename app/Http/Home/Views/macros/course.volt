{%- macro level_badge(level) %}
    {% if level == 1 %}
        <span class="layui-badge layui-bg-green">{{ course_level(level) }}</span>
    {% elseif level == 2 %}
        <span class="layui-badge layui-bg-blue">{{ course_level(level) }}</span>
    {% elseif level == 3 %}
        <span class="layui-badge layui-bg-red">{{ course_level(level) }}</span>
    {% endif %}
{%- endmacro %}

{%- macro star_info(rating) %}
    {% set stars = [1,2,3,4,5] %}
    {% for val in stars if val <= rating %}
        <i class="layui-icon layui-icon-star-fill"></i>
    {% endfor %}
{%- endmacro %}

{%- macro course_card(course) %}
    {% set course_url = url({'for':'home.course.show','id':course.id,'slug':course.slug}) %}
    <div class="course-card">
        <div class="level">{{ level_badge(course.level) }}</div>
        <div class="rating">{{ star_info(course.rating) }}</div>
        <div class="cover">
            <a href="{{ course_url }}" target="_blank">
                <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}" title="{{ course.title }}">
            </a>
        </div>
        <div class="info">
            <div class="title">
                <a href="{{ course_url }}" title="{{ course.title }}" target="_blank">{{ course.title }}</a>
            </div>
            <div class="meta">
                {% if course.regular_price == 0 %}
                    <span class="free">{{ locale._('course_free') }}</span>
                {% elseif course.vip_price == 0 %}
                    <span class="free">{{ locale._('course_vip_free') }}</span>
                {% else %}
                    <span class="price">{{ course.regular_price|human_price }}</span>
                {% endif %}
                <span class="lesson">{{ locale._('course_lessons_x',{'x':course.lesson_count|human_number}) }}</span>
                <span class="user">{{ locale._('course_users_x',{'x':course.user_count|human_number}) }}</span>
            </div>
        </div>
    </div>
{%- endmacro %}

{%- macro sidebar_course_card(course) %}
    {% set course_url = url({'for':'home.course.show','id':course.id,'slug':course.slug}) %}
    <div class="sidebar-course-card" title="{{ course.title }}">
        <div class="cover">
            <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
        </div>
        <div class="info">
            <div class="title layui-elip">
                <a href="{{ course_url }}" title="{{ course.title }}" target="_blank">{{ course.title }}</a>
            </div>
            <div class="meta">
                {% if course.regular_price == 0 %}
                    <span class="free">{{ locale._('course_free') }}</span>
                {% elseif course.vip_price == 0 %}
                    <span class="free">{{ locale._('course_vip_free') }}</span>
                {% else %}
                    <span class="price">{{ course.regular_price|human_price }}</span>
                {% endif %}
                <span class="lesson">{{ locale._('course_lessons_x',{'x':course.lesson_count|human_number}) }}</span>
            </div>
        </div>
    </div>
{%- endmacro %}
