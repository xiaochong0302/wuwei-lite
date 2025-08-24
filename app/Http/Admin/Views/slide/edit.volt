{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.slide.update','id':slide.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_slide') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('slide_cover') }}</label>
            <div class="layui-input-inline">
                <img id="img-cover" class="kg-slide-cover" src="{{ slide.cover }}">
                <input type="hidden" name="cover" value="{{ slide.cover }}">
            </div>
            <div class="layui-input-inline" style="padding-top:15px;">
                <button id="change-cover" class="layui-btn layui-btn-sm" type="button">{{ locale._('upload') }}</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('slide_title') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ slide.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('slide_link') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="link" value="{{ slide.link }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('sort_order') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ slide.priority }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('published') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if slide.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if slide.published == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}
