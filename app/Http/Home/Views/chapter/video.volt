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
            <div class="player-wrap wrap">
                <div id="play-mask" class="play-mask">
                    <span class="layui-icon layui-icon-play"></span>
                </div>
                <div id="player"></div>
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
        <input type="hidden" name="chapter.cover" value="{{ chapter.course.cover }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
        <input type="hidden" name="chapter.play_urls" value='{{ chapter.play_urls|json_encode }}'>
        <input type="hidden" name="chapter.settings" value='{{ chapter.settings|json_encode }}'>
        <input type="hidden" name="chapter.me.position" value="{{ chapter.me.position }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/dplayer/hls.min.js') }}
    {{ js_include('lib/dplayer/DPlayer.min.js') }}
    {{ js_include('home/js/chapter.show.js') }}
    {{ js_include('home/js/chapter.vod.player.js') }}
    {{ js_include('home/js/comment.js') }}

{% endblock %}
