<!DOCTYPE html>
<html lang="{{ site_info.language }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrfToken.getToken() }}">
    <title>locale._('admin_panel')</title>
    {{ icon_link('favicon.ico') }}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('admin/css/common.css') }}
    {% block link_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>
<body class="kg-body">
{% block content %}{% endblock %}

{{ partial('partials/js_vars') }}
{{ partial('partials/layui_i18n') }}
{{ js_include('lib/layui/layui.js') }}
{{ js_include('admin/js/common.js') }}

{% block include_js %}{% endblock %}
{% block inline_js %}{% endblock %}
</body>
</html>
