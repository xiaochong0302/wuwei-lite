{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.review.update','id':review.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_review') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('review_rating') }}</label>
            <div class="layui-input-block">
                {% for value in 1..5 %}
                    {% set checked = value == review.rating ? 'checked="checked"' : '' %}
                    <input type="radio" name="rating" value="{{ value }}" title="{{ value }}" {{ checked }}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('review_content') }}</label>
            <div class="layui-input-block">
                <textarea name="content" class="layui-textarea">{{ review.content }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('review_status') }}</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    {% set checked = value == review.published ? 'checked="checked"' : '' %}
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

{% block inline_js %}

    <script>

        layui.use(['jquery', 'rate'], function () {

            var $ = layui.jquery;
            var rate = layui.rate;

            var $rating = $('input[name=rating]');

            rate.render({
                elem: '#rating',
                value: $rating.val(),
                choose: function (value) {
                    $rating.val(value);
                }
            });
        });

    </script>

{% endblock %}
