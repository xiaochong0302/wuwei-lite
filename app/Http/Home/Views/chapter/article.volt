{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_url = url({'for':'home.course.show','id':chapter.course.id,'slug':chapter.course.slug}) %}
    {% set learning_url = url({'for':'home.chapter.learning','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> {{ locale._('back_course') }}</a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="article-info wrap">
                <div class="content markdown-body kg-zoom">{{ chapter.content }}</div>
            </div>
            <div id="comment-anchor"></div>
            {% if chapter.comment_enabled == 1 %}
                <div class="vod-comment wrap">
                    {{ partial('chapter/comment') }}
                </div>
            {% else %}
                <div class="wrap center gray">
                    <i class="layui-icon layui-icon-lock"></i> {{ locale._('comment_closed_tips') }}
                </div>
            {% endif %}
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/catalog') }}
        </div>
    </div>

    <div class="layout-sticky">
        {{ partial('chapter/sticky') }}
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/chapter.read.js') }}
    {{ js_include('home/js/chapter.show.js') }}
    {{ js_include('home/js/comment.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}
