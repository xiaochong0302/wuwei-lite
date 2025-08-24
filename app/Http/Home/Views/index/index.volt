{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {%- macro show_courses(courses) %}
        <div class="index-course-list">
            <div class="layui-row layui-col-space20">
                {% for course in courses %}
                    <div class="layui-col-md3">
                        {{ course_card(course) }}
                    </div>
                {% endfor %}
            </div>
        </div>
    {%- endmacro %}

    {% if slides|length > 0 %}
        <div class="index-carousel wrap">
            <div class="layui-carousel" id="carousel">
                <div class="carousel" carousel-item>
                    {% for slide in slides %}
                        <div class="item">
                            <a href="{{ slide.link }}" target="_blank">
                                <img class="carousel" src="{{ slide.cover }}!slide_1100" alt="{{ slide.title }}">
                            </a>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
    {% endif %}

    {% if featured_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">{{ locale._('featured_courses') }}</div>
            <div class="content">
                {{ show_courses(featured_courses) }}
            </div>
        </div>
    {% endif %}

    {% if latest_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">{{ locale._('latest_courses') }}</div>
            <div class="content">
                {{ show_courses(latest_courses) }}
            </div>
        </div>
    {% endif %}

    {% if popular_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">{{ locale._('popular_courses') }}</div>
            <div class="content">
                {{ show_courses(popular_courses) }}
            </div>
        </div>
    {% endif %}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/index.js') }}

{% endblock %}
