{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro content_title(model) %}
        {% if model == 1 %}
            {{ locale._('chapter_video_info') }}
        {% elseif model == 3 %}
            {{ locale._('chapter_article_info') }}
        {% endif %}
    {%- endmacro %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{ locale._('edit_lesson') }}</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('chapter_basic_info') }}</li>
            <li>{{ content_title(chapter.model) }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('chapter/edit_lesson_basic') }}
            </div>
            <div class="layui-tab-item">
                {% if chapter.model == 1 %}
                    {{ partial('chapter/edit_lesson_video') }}
                {% elseif chapter.model == 3 %}
                    {{ partial('chapter/edit_lesson_article') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {% if chapter.model == 1 %}
        {{ css_link('lib/webuploader/webuploader.css') }}
    {% elseif chapter.model == 3 %}
        {{ css_link('lib/vditor/dist/index.css') }}
    {% endif %}

{% endblock %}

{% block include_js %}

    {% if chapter.model == 1 %}

        {{ js_include('lib/jquery.min.js') }}
        {{ js_include('lib/webuploader/webuploader.min.js') }}
        {{ js_include('admin/js/media.upload.js') }}

    {% elseif chapter.model == 3 %}

        {{ js_include('lib/vditor/dist/index.min.js') }}
        {{ js_include('admin/js/vditor.js') }}

    {% endif %}

{% endblock %}
