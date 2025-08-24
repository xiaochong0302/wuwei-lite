{% if pager.total_items > 0 %}
    <div class="user-list teacher-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set teacher_url = url({'for':'home.teacher.show','id':item.id,'name':item.name}) %}
                <div class="layui-col-md3">
                    <div class="user-card" title="{{ item.about }}">
                        <div class="avatar">
                            <a href="{{ teacher_url }}" target="_blank">
                                <img src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                            </a>
                        </div>
                        <div class="name layui-elip">
                            <a href="{{ teacher_url }}" target="_blank">{{ item.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ item.title|default(locale._('title_educator')) }}</div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
