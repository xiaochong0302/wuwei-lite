{% if pager.total_items > 0 %}
    <div class="user-list vip-user-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                <div class="layui-col-md3">
                    <div class="user-card">
                        <div class="avatar">
                            <a href="javascript:" title="{{ item.about }}">
                                <img src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                            </a>
                        </div>
                        <div class="name layui-elip">
                            <a href="javascript:" title="{{ item.about }}">{{ item.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ item.title|default(locale._('title_achiever')) }}</div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
