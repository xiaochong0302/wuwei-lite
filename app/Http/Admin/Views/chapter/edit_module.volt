{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.update','id':chapter.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_module') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_title') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ chapter.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_summary') }}</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="summary">{{ chapter.summary }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('sort_order') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ chapter.priority }}" lay-verify="number">
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
