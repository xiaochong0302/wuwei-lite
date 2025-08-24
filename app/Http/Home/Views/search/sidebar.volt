{% if related_queries %}
    <div class="layui-card">
        <div class="layui-card-header">{{ locale._('search_related_queries') }}</div>
        <div class="layui-card-body">
            {% for query in related_queries %}
                {% set url = url({'for':'home.search.index'},{'type':type,'query':query}) %}
                <a class="layui-badge-rim query-badge" href="{{ url }}">{{ query }}</a>
            {% endfor %}
        </div>
    </div>
{% endif %}

<div class="sidebar" id="sidebar-course-list" data-url="{{ url({'for':'home.widget.featured_courses'}) }}"></div>

{% if hot_queries %}
    <div class="layui-card">
        <div class="layui-card-header">{{ locale._('search_hot_queries') }}</div>
        <div class="layui-card-body">
            {% for query in hot_queries %}
                {% set url = url({'for':'home.search.index'},{'type':type,'query':query}) %}
                <a class="layui-badge-rim query-badge" href="{{ url }}">{{ query }}</a>
            {% endfor %}
        </div>
    </div>
{% endif %}
