{%- macro model_icon(model) %}
    {% if model == 1 %}
        <i class="iconfont icon-video"></i>
    {% elseif model == 3 %}
        <i class="iconfont icon-article"></i>
    {% endif %}
{%- endmacro %}

{%- macro show_lesson_list(parent,chapter) %}
    <ul class="sidebar-lesson-list">
        {% for lesson in parent.children %}
            {% set url = url({'for':'home.chapter.show','id':lesson.id,'slug':lesson.slug}) %}
            {% set active = chapter.id == lesson.id ? 'active' : 'normal' %}
            {% set priv = lesson.me.owned == 1 ? 'allow' : 'deny' %}
            <li class="sidebar-lesson {{ priv }} {{ active }}" data-url="{{ url }}">
                <span class="model">{{ model_icon(lesson.model) }}</span>
                <span class="title" title="{{ lesson.title }}">{{ lesson.title }}</span>
                {% if lesson.me.owned == 0 %}
                    <span class="lock"><i class="iconfont icon-lock"></i></span>
                {% endif %}
            </li>
        {% endfor %}
    </ul>
{%- endmacro %}

<div class="layui-card sidebar-card sidebar-catalog">
    <div class="layui-card-header">{{ locale._('course_catalog') }}</div>
    <div class="layui-card-body">
        {% if catalog|length > 1 %}
            <div class="sidebar-chapter-list">
                {% for item in catalog %}
                    <div class="sidebar-chapter layui-elip">{{ item.title }}</div>
                    {{ show_lesson_list(item,chapter) }}
                {% endfor %}
            </div>
        {% else %}
            {{ show_lesson_list(catalog[0],chapter) }}
        {% endif %}
    </div>
</div>
