{% set tc_val = request.get('tc','int','all') %}
{% set sc_val = request.get('sc','int','all') %}
{% set level_val = request.get('level','trim','all') %}
{% set sort_val = request.get('sort','trim','latest') %}

<div class="filter-toggle">
    <span class="layui-icon layui-icon-up"></span>
</div>

<div class="filter-wrap wrap">
    {% if top_categories %}
        <div class="filter-group">
            <div class="title">{{ locale._('filter_subject') }}</div>
            <div class="content">
                {% for category in top_categories %}
                    {% set class = tc_val == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
    {% endif %}
    {% if sub_categories %}
        <div class="filter-group">
            <div class="title">{{ locale._('filter_category') }}</div>
            <div class="content">
                {% for category in sub_categories %}
                    {% set class = sc_val == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
    {% endif %}
    <div class="filter-group">
        <div class="title">{{ locale._('filter_level') }}</div>
        <div class="content">
            {% for level in levels %}
                {% set class = level_val == level.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ level.url }}">{{ level.name }}</a>
            {% endfor %}
        </div>
    </div>
    <div class="filter-group">
        <div class="title">{{ locale._('filter_sort') }}</div>
        <div class="content">
            {% for sort in sorts %}
                {% set class = sort_val == sort.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ sort.url }}">{{ sort.name }}</a>
            {% endfor %}
        </div>
    </div>
</div>
