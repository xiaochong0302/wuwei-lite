{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'admin.package.update','id':package.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{ locale._('edit_package') }}</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">{{ locale._('package_basic_info') }}</li>
            <li>{{ locale._('package_sales_info') }}</li>
            <li>{{ locale._('package_related_courses') }}</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('package/edit_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('package/edit_sale') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('package/edit_course') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
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
