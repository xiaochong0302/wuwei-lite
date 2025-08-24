{% extends 'templates/layer.volt' %}

{% block content %}

    {% set create_url = url({'for':'home.review.create'}) %}

    <form class="layui-form review-form" method="POST" action="{{ create_url }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('uc_course') }}</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ review.course.title }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('uc_rating') }}</label>
            <div class="layui-input-block">
                <div id="rating"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('uc_review') }}</label>
            <div class="layui-input-block">
                <textarea name="content" class="layui-textarea" lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="reset" class="layui-btn layui-btn-primary">{{ locale._('reset') }}</button>
                <input type="hidden" name="course_id" value="{{ request.get('course_id') }}">
                <input type="hidden" name="rating" value="5">
            </div>
        </div>
    </form>
{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.review.js') }}

{% endblock %}
