{{ partial('macros/course') }}

{% if courses|length > 0 %}
    <div class="layui-card">
        <div class="layui-card-header">{{ locale._('featured_courses') }}</div>
        <div class="layui-card-body">
            {% for course in courses %}
                {{ sidebar_course_card(course) }}
            {% endfor %}
        </div>
    </div>
{% endif %}
