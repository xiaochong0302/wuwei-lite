{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.role.update','id':role.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('edit_role') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('role_name') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ role.name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('role_summary') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="summary" value="{{ role.summary }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('role_permissions') }}</label>
            <div class="layui-input-block">
                {% for key,level in auth_nodes %}
                    <table class="layui-table">
                        {% for key2,level2 in level.children %}
                            <tr>
                                {% if key2 == 0 %}
                                    <td width="15%" rowspan="{{ level.children|length }}">{{ level.title }}</td>
                                {% endif %}
                                <td width="15%">{{ level2.title }}</td>
                                <td>
                                    {% for level3 in level2.children %}
                                        <input type="checkbox" name="routes[]" title="{{ level3.title }}" value="{{ level3.route }}" {% if level3.route in role.routes %}checked="checked"{% endif %}>
                                    {% endfor %}
                                </td>
                            </tr>
                        {% endfor %}
                    </table>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
            </div>
        </div>
    </form>

{% endblock %}
