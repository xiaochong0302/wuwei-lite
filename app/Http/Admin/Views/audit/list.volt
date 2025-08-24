{% extends 'templates/main.volt' %}

{% block content %}

    {% set search_url = url({'for':'admin.audit.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>{{ locale._('audit_history') }}</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>{{ locale._('search_audit') }}
            </a>
        </div>
    </div>

    <table class="layui-table kg-table">
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
            <th>{{ locale._('audit_user_id') }}</th>
            <th>{{ locale._('audit_user_name') }}</th>
            <th>{{ locale._('audit_user_ip') }}</th>
            <th>{{ locale._('audit_req_route') }}</th>
            <th>{{ locale._('audit_req_path') }}</th>
            <th>{{ locale._('create_time') }}</th>
            <th>{{ locale._('actions') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'admin.audit.show','id':item.id}) %}
            <tr>
                <td>{{ item.user_id }}</td>
                <td>{{ item.user_name }}</td>
                <td>{{ item.user_ip }}</td>
                <td>{{ item.req_route }}</td>
                <td>{{ item.req_path }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                <td class="center">
                    <button class="kg-view layui-btn layui-btn-sm" data-url="{{ show_url }}" data-title="{{ locale._("audit_req_content") }}">{{ locale._('details') }}</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('.kg-view').on('click', function () {
                var url = $(this).data('url');
                var title = $(this).data('title');
                layer.open({
                    type: 2,
                    title: title,
                    area: ['640px', '360px'],
                    content: url
                });
            });

        });

    </script>

{% endblock %}
