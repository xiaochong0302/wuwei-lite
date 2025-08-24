{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_url = url({'for':'home.course.show','id':chapter.course.id,'slug':chapter.course.slug}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> {{ locale._('back_course') }}</a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="article-info wrap">
                <div class="content">{{ chapter.summary }}</div>
            </div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/catalog') }}
        </div>
    </div>

    <div class="layout-sticky">
        {{ partial('chapter/sticky') }}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/chapter.show.js') }}

{% endblock %}
