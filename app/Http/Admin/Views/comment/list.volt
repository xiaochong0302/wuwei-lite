{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

    {% set search_url = url({'for':'admin.comment.search'}) %}
    {% set batch_delete_url = url({'for':'admin.comment.batch_delete'}) %}
    {% set batch_moderate_url = url({'for':'admin.comment.batch_moderate'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('comment_manager') }}</cite></a>
            </span>
        </div>
    </div>

    <div class="kg-nav kg-btn-group">
        <div class="kg-nav-left">
            <span class="layui-btn layui-btn-sm layui-bg-green kg-batch" data-url="{{ batch_moderate_url }}?type=approve">{{ locale._('batch_approve') }}</span>
            <span class="layui-btn layui-btn-sm layui-bg-blue kg-batch" data-url="{{ batch_moderate_url }}?type=reject">{{ locale._('batch_reject') }}</span>
            <span class="layui-btn layui-btn-sm layui-bg-red kg-batch" data-url="{{ batch_delete_url }}">{{ locale._('batch_delete') }}</span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm layui-bg-green" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_comment') }}
            </a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col width="5%">
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th><input class="all" type="checkbox" lay-filter="all"></th>
            <th>{{ locale._('comment_author') }}</th>
            <th>{{ locale._('comment_content') }}</th>
            <th>{{ locale._('comment_likes') }}</th>
            <th>{{ locale._('comment_status') }}</th>
            <th>{{ locale._('create_time') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_url = url({'for':'admin.user.show','id':item.owner_id}) %}
            {% set chapter_url = url({'for':'home.chapter.show','id':item.chapter.id,'slug':item.chapter.slug}) %}
            {% set edit_url = url({'for':'admin.comment.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.comment.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.comment.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.comment.restore','id':item.id}) %}
            <tr>
                <td><input class="item" type="checkbox" value="{{ item.id }}" lay-filter="item"></td>
                <td>
                    <p><a href="{{ user_url }}">{{ item.owner.name }}</a></p>
                    <p>{{ item.owner.id }}</p>
                </td>
                <td>
                    <p><a href="{{ chapter_url }}" target="_blank">{{ item.chapter.title }}</a></p>
                    <p class="layui-elip kg-item-elip" title="{{ item.content }}"><a href="{{ edit_url }}">{{ item.content }}</a></p>
                </td>
                <td>{{ item.like_count }}</td>
                <td>{{ comment_status(item.published) }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
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
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}
