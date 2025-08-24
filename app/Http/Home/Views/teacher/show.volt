{% extends 'templates/main.volt' %}

{% block content %}

    {% set courses_url = url({'for':'home.teacher.courses','id':user.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">{{ locale._('page_home') }}</a>
            <a href="{{ url({'for':'home.teacher.list'}) }}">{{ locale._('page_teachers') }}</a>
            <a><cite>{{ user.name }}</cite></a>
        </span>
    </div>

    <div class="layui-card user-page">
        <div class="layui-card-header">{{ locale._('about_me') }}</div>
        <div class="layui-card-body">
            <div class="user-profile">
                <div class="left">
                    <div class="avatar"><img src="{{ user.avatar }}" alt="{{ user.name }}"></div>
                </div>
                <div class="right">
                    <div class="title">{{ user.title|default(locale._('title_educator')) }}</div>
                    <div class="about">{{ user.about }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-card user-page">
        <div class="layui-card-header">{{ locale._('my_courses') }}</div>
        <div class="layui-card-body">
            <div class="layui-padding-2" id="course-list" data-url="{{ courses_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/teacher.show.js') }}

{% endblock %}
