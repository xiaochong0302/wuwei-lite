{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.course.update','id':course.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{ locale._('edit_course') }}</legend>
    </fieldset>

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">{{ locale._('course_basic_info') }}</li>
            <li>{{ locale._('course_sales_info') }}</li>
            <li>{{ locale._('course_overview') }}</li>
            <li>{{ locale._('course_related') }}</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('course/edit_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_sale') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_overview') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_related') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('lib/vditor/dist/index.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('lib/vditor/dist/index.min.js') }}
    {{ js_include('admin/js/vditor.js') }}
    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'helper'], function () {

            var helper = layui.helper;

            xmSelect.render({
                el: '#xm-course-ids',
                name: 'xm_course_ids',
                tips: helper.locale('xm_select_tips'),
                empty: helper.locale('xm_select_empty_tips'),
                searchTips: helper.locale('xm_select_search_tips'),
                max: 15,
                autoRow: true,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_courses|json_encode }}
            });

        });

    </script>

{% endblock %}
