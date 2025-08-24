{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.comment.update','id':comment.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_comment') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('comment_content') }}</label>
            <div class="layui-input-block">
                <textarea name="content" class="layui-textarea">{{ comment.content }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('comment_status') }}</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    {% set checked = value == comment.published ? 'checked="checked"' : '' %}
                    <input type="radio" name="published" value="{{ value }}" title="{{ title }}" {{ checked }}>
                {% endfor %}
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
