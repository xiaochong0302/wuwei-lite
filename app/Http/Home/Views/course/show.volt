{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">{{ locale._('page_home') }}</a>
            <a href="{{ url({'for':'home.course.list'}) }}">{{ locale._('page_courses') }}</a>
            <a><cite>{{ course.title }}</cite></a>
        </span>
    </div>

    {{ partial('course/show_meta') }}

    <div class="layout-main">

        {% set show_tab_chapters = course.lesson_count > 0 %}
        {% set show_tab_reviews = course.review_count > 0 %}
        {% set show_tab_resources = course.resource_count > 0 %}
        {% set show_tab_packages = course.package_count > 0 %}
        {% set show_sidebar_related = 1 %}

        <div class="layout-content">
            <div class="course-tab-wrap wrap">
                <div class="layui-tab layui-tab-brief course-tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">{{ locale._('course_overview') }}</li>
                        <li>{{ locale._('course_outline') }}</li>
                        {% if show_tab_reviews %}
                            <li>{{ locale._('course_reviews') }}</li>
                        {% endif %}
                        {% if show_tab_packages %}
                            <li>{{ locale._('course_packages') }}</li>
                        {% endif %}
                        {% if show_tab_resources %}
                            <li>{{ locale._('course_resources') }}</li>
                        {% endif %}
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="course-details markdown-body kg-zoom">{{ course.details }}</div>
                        </div>
                        <div class="layui-tab-item">
                            {{ partial('course/show_catalog') }}
                        </div>
                        {% if show_tab_reviews %}
                            {% set reviews_url = url({'for':'home.course.reviews','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-reviews" data-url="{{ reviews_url }}"></div>
                        {% endif %}
                        {% if show_tab_packages %}
                            {% set packages_url = url({'for':'home.course.packages','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-packages" data-url="{{ packages_url }}"></div>
                        {% endif %}
                        {% if show_tab_resources %}
                            {% set resources_url = url({'for':'home.course.resources','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-resources" data-url="{{ resources_url }}"></div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>

        <div class="layout-sidebar">
            {{ partial('course/show_order') }}
            {{ partial('course/show_teacher') }}
            {% if show_sidebar_related %}
                {% set related_url = url({'for':'home.course.related','id':course.id}) %}
                <div class="sidebar" id="sidebar-related" data-url="{{ related_url }}"></div>
            {% endif %}
        </div>

    </div>

    <div class="layout-sticky">
        {{ partial('course/sticky') }}
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/course.show.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}
