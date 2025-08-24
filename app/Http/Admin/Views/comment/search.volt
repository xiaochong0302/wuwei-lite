{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.comment.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('search_comment') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('comment_author') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" placeholder="{{ locale._('account_search_tips') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('chapter_id') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="chapter_id" placeholder="{{ locale._('exact_match_tips') }}">
            </div>
        </div>
        <div class="layui-form-item" id="time-range">
            <label class="layui-form-label">{{ locale._('create_time') }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="start-time" type="text" name="create_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="end-time" type="text" name="create_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('comment_status') }}</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    <input type="checkbox" name="published[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
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
