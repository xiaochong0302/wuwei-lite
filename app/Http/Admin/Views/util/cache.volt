{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.util.cache'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('index_page_caches') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('cache_types') }}</label>
            <div class="layui-input-block">
                <input type="checkbox" name="items[]" value="slide" title="{{ locale._('index_slides') }}" checked="checked">
                <input type="checkbox" name="items[]" value="new_course" title="{{ locale._('index_new_courses') }}" checked="checked">
                <input type="checkbox" name="items[]" value="popular_course" title="{{ locale._('index_popular_courses') }}" checked="checked">
                <input type="checkbox" name="items[]" value="featured_course" title="{{ locale._('index_featured_courses') }}" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('flush') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                <input type="hidden" name="section" value="index_cache">
            </div>
        </div>
    </form>

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.util.cache'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('opcache') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('cache_types') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="scope" value="all" title="{{ locale._('all_files') }}" checked="checked">
                <input type="radio" name="scope" value="diff" title="{{ locale._('diff_files') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('flush') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                <input type="hidden" name="section" value="op_cache">
            </div>
        </div>
    </form>

{% endblock %}
