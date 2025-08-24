{% extends 'templates/main.volt' %}

{% block content %}

    {% set back_url = url({'for':'admin.course.list'}) %}
    {% set add_module_url = url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'module'}) %}
    {% set add_lesson_url = url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'lesson'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>{{ locale._('back') }}</a>
                <a><cite>{{ course.title }}</cite></a>
                <a><cite>{{ locale._('module_manager') }}</cite></a>
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
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>{{ locale._('id') }}</th>
            <th>{{ locale._('chapter_title') }}</th>
            <th>{{ locale._('chapter_lessons') }}</th>
            <th>{{ locale._('sort_order') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in modules %}
            {% set edit_url = url({'for':'admin.chapter.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.chapter.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.chapter.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.chapter.restore','id':item.id}) %}
            {% set lessons_url = url({'for':'admin.chapter.lessons','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td>
                    <a href="{{ lessons_url }}"><i class="layui-icon layui-icon-add-circle"></i> {{ item.title }}</a>
                </td>
                <td>{{ item.lesson_count }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="{{ locale._('sort_order_tips') }}" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">{{ locale._('actions') }} <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ edit_url }}">{{ locale._('edit') }}</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">{{ locale._('delete') }}</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">{{ locale._('restore') }}</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ lessons_url }}">{{ locale._('chapter_lessons') }}</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}
