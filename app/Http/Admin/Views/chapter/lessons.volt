{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro attrs_info(model,attrs) %}
        {% set attrs = attrs|json_decode %}
        {% if model == 1 %}
            <span>{{ locale._('chapter_model') }}：{{ chapter_model(model) }}</span>
            {% if attrs.duration > 0 %}
                <span>{{ locale._('video_duration') }}：{{ attrs.duration|duration }}</span>
            {% else %}
                <span>{{ locale._('video_duration') }}：N/A</span>
            {% endif %}
        {% elseif model == 3 %}
            <span>{{ locale._('chapter_model') }}：{{ chapter_model(model) }}</span>
            {% if attrs.word_count > 0 %}
                <span>{{ locale._('article_words') }}：{{ attrs.word_count }}</span>
            {% else %}
                <span>{{ locale._('article_words') }}：N/A</span>
            {% endif %}
        {% endif %}
    {%- endmacro %}

    {% set back_url = url({'for':'admin.course.modules','id':course.id}) %}
    {% set add_module_url = url({'for':'admin.chapter.add'},{'type':'module','course_id':course.id}) %}
    {% set add_lesson_url = url({'for':'admin.chapter.add'},{'type':'lesson','course_id':course.id,'parent_id':chapter.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>{{ locale._('back') }}</a>
                <a><cite>{{ course.title }}</cite></a>
                <a><cite>{{ chapter.title }}</cite></a>
                <a><cite>{{ locale._('lesson_manager') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_module_url }}">
                <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_module') }}
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ add_lesson_url }}">
                <i class="layui-icon layui-icon-add-1"></i>{{ locale._('add_lesson') }}
            </a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('id') }}</th>
            <th>{{ locale._('chapter_title') }}</th>
            <th>{{ locale._('chapter_comments') }}</th>
            <th>{{ locale._('chapter_likes') }}</th>
            <th>{{ locale._('sort_order') }}</th>
            <th>{{ locale._('published') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in lessons %}
            {% set chapter_url = url({'for':'home.chapter.show','id':item.id,'slug':item.slug}) %}
            {% set edit_url = url({'for':'admin.chapter.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.chapter.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.chapter.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.chapter.restore','id':item.id}) %}
            {% set comments_url = url({'for':'admin.comment.list'},{'chapter_id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td>
                    <p><a href="{{ edit_url }}">{{ item.title }}</a></p>
                    <p>{{ attrs_info(item.model,item.attrs) }}</p>
                </td>
                <td>{{ item.comment_count }}</td>
                <td>{{ item.like_count }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="{{ locale._('sort_order_tips') }}" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="{{ locale._('switch_text') }}" lay-filter="go" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ chapter_url }}" target="_blank">{{ locale._('view') }}</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">{{ locale._('edit') }}</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">{{ locale._('delete') }}</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">{{ locale._('restore') }}</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ comments_url }}">{{ locale._('chapter_comments') }}</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}
