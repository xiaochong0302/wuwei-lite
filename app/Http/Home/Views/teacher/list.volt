{% extends 'templates/main.volt' %}

{% block content %}

    {% set pager_url = url({'for':'home.teacher.pager'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">{{ locale._('home') }}</a>
        <a><cite>{{ locale._('page_teachers') }}</cite></a>
    </div>

    <div id="teacher-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/teacher.list.js') }}

{% endblock %}
