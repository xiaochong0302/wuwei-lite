{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'admin.page.update','id':page.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{ locale._('edit_page') }}</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('page_tab_basic') }}</li>
            <li>{{ locale._('page_tab_seo') }}</li>
            <li>{{ locale._('page_tab_content') }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('page/edit_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('page/edit_seo') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('page/edit_desc') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('lib/vditor/dist/index.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/vditor/dist/index.min.js') }}
    {{ js_include('admin/js/vditor.js') }}

{% endblock %}
