{{ partial('macros/course') }}

{% if pager.total_items > 0 %}
    <div class="search-course-list">
        {% for item in pager.items %}
            {% set course_url = url({'for':'home.course.show','id':item.id,'slug':item.slug}) %}
            <div class="search-course-card">
                <div class="left">
                    <div class="level">
                        <span class="layui-badge layui-bg-green">{{ course_level(item.level) }}</span>
                    </div>
                    <div class="rating">{{ star_info(item.rating) }}</div>
                    <div class="cover">
                        <a href="{{ course_url }}" target="_blank">
                            <img src="{{ item.cover }}!cover_270" alt="{{ item.title|striptags }}">
                        </a>
                    </div>
                </div>
                <div class="right">
                    <div class="title layui-elip">
                        <a href="{{ course_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span>{{ locale._('course_lessons_x',['x':item.lesson_count|human_number]) }}</span>
                        <span>{{ locale._('course_users_x',['x':item.user_count|human_number]) }}</span>
                        <span>{{ locale._('course_reviews_x',['x':item.review_count|human_number]) }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
{% else %}
    {{ partial('search/empty') }}
{% endif %}
