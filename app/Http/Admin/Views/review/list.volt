{% extends 'templates/main.volt' %}

{% block content %}

    {% set search_url = url({'for':'admin.review.search'}) %}
    {% set batch_delete_url = url({'for':'admin.review.batch_delete'}) %}
    {% set batch_moderate_url = url({'for':'admin.review.batch_moderate'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('review_manager') }}</cite></a>
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
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_review') }}
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
            <th>{{ locale._('review_author') }}</th>
            <th>{{ locale._('review_content') }}</th>
            <th>{{ locale._('review_rating') }}</th>
            <th>{{ locale._('review_status') }}</th>
            <th>{{ locale._('create_time') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_url = url({'for':'admin.user.show','id':item.owner_id}) %}
            {% set course_url = url({'for':'home.course.show','id':item.course.id,'slug':item.course.slug}) %}
            {% set edit_url = url({'for':'admin.review.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.review.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.review.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.review.restore','id':item.id}) %}
            <tr>
                <td><input class="item" type="checkbox" value="{{ item.id }}" lay-filter="item"></td>
                <td>
                    <p><a href="{{ user_url }}">{{ item.owner.name }}</a></p>
                    <p>{{ item.owner.id }}</p>
                </td>
                <td>
                    <p><a href="{{ course_url }}" title="{{ item.course.title }}" target="_blank">{{ item.course.title }}</a></p>
                    <p class="layui-elip kg-item-elip"><a class="gray" href="{{ edit_url }}" title="{{ item.content }}">{{ item.content }}</a></p>
                </td>
                <td>{{ item.rating }}</td>
                <td>{{ review_status(item.published) }}</td>
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
