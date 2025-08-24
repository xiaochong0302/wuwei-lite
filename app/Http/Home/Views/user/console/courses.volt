{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">{{ locale._('my_courses') }}</span>
                </div>
                {% if pager.total_items > 0 %}
                    <table class="layui-table" lay-size="lg">
                        <colgroup>
                            <col width="40%">
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>{{ locale._('uc_course') }}</th>
                            <th>{{ locale._('uc_study_time') }}</th>
                            <th>{{ locale._('uc_progress') }}</th>
                            <th>{{ locale._('uc_expiry') }}</th>
                            <th>{{ locale._('actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'home.course.show','id':item.course.id,'slug':item.course.slug}) %}
                            {% set review_url = url({'for':'home.review.add'},{'course_id':item.course.id}) %}
                            {% set allow_review = item.progress > 30 and item.course.review_enabled == 1 and item.reviewed == 0 %}
                            <tr>
                                <td><a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a></td>
                                <td>{{ item.duration > 0 ? item.duration|duration : 'N/A' }}</td>
                                <td>{{ item.progress }}%</td>
                                <td>{{ item.expiry_time > 0 ? date('Y-m-d',item.expiry_time) : 'N/A' }}</td>
                                <td>
                                    {% if allow_review %}
                                        <button class="layui-btn layui-btn-sm btn-add-review" data-url="{{ review_url }}">{{ locale._('uc_review') }}</button>
                                    {% else %}
                                        <button class="layui-btn layui-btn-sm layui-btn-disabled" title="{{ locale._('unlock_review_tips') }}">{{ locale._('uc_review') }}</button>
                                    {% endif %}
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}
