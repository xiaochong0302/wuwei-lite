{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.course.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('search_course') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('id') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('course_title') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" placeholder="{{ locale._('wildcard_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('course_category') }}</label>
            <div class="layui-input-block">
                <select name="category_id" lay-search="true">
                    <option value="">{{ locale._('select') }}</option>
                    {% for option in category_options %}
                        <option value="{{ option.id }}">{{ option.name }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('course_teacher') }}</label>
            <div class="layui-input-block">
                <select name="teacher_id" lay-search="true">
                    <option value="">{{ locale._('select') }}</option>
                    {% for option in teacher_options %}
                        <option value="{{ option.id }}">{{ option.name }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('course_level') }}</label>
            <div class="layui-input-block">
                {% for value,title in level_types %}
                    <input type="checkbox" name="level[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('free') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="free" value="1" title="{{ locale._('yes') }}">
                <input type="radio" name="free" value="0" title="{{ locale._('no') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('featured') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="featured" value="1" title="{{ locale._('yes') }}">
                <input type="radio" name="featured" value="0" title="{{ locale._('no') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('published') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="{{ locale._('yes') }}">
                <input type="radio" name="published" value="0" title="{{ locale._('no') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('deleted') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="deleted" value="1" title="{{ locale._('yes') }}">
                <input type="radio" name="deleted" value="0" title="{{ locale._('no') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
            </div>
        </div>
    </form>

{% endblock %}
