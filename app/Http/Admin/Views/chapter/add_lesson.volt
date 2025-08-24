{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('add_lesson') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_module') }}</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-verify="required">
                    <option value="">{{ locale._('select') }}</option>
                    {% for module in modules %}
                        <option value="{{ module.id }}" {% if parent_id == module.id %}selected{% endif %}>{{ module.title }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_model') }}</label>
            <div class="layui-input-block">
                {% for value,title in model_types %}
                    {% set checked = value == 1 ? 'checked="checked"' : '' %}
                    <input type="radio" name="model" value="{{ value }}" title="{{ title }}" lay-filter="model" {{ checked }}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux" id="model-tips"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_title') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_summary') }}</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="summary"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
                <input type="hidden" name="course_id" value="{{ course.id }}">
                <input type="hidden" name="model_vod_tips" value="{{ locale._('model_vod_tips') }}">
                <input type="hidden" name="model_read_tips" value="{{ locale._('model_read_tips') }}">
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'layer'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            var modelTips = {
                '1': $('input[name=model_vod_tips]').val(),
                '3': $('input[name=model_read_tips]').val(),
            };

            var modelTipsBlock = $('#model-tips');

            form.on('radio(model)', function (data) {
                modelTipsBlock.html(modelTips[data.value]);
            });

            modelTipsBlock.html(modelTips['1']);
        });

    </script>

{% endblock %}
