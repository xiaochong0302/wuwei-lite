{{ partial('macros/course') }}

{% if pager.total_items > 0 %}
    <div class="review-list">
        {% for item in pager.items %}
            {% set like_url = url({'for':'home.review.like','id':item.id}) %}
            {% set like_class = item.me.liked == 1 ? 'liked' : '' %}
            {% set vip_class = item.owner.vip == 1 ? 'vip' : '' %}
            <div class="comment-card review-card">
                <div class="avatar {{ vip_class }}">
                    <img src="{{ item.owner.avatar }}!avatar_160" alt="{{ item.owner.name }}">
                </div>
                <div class="info">
                    <div class="rating">{{ star_info(item.rating) }}</div>
                    <div class="user">
                        <span class="name">@{{ item.owner.name }}</span>
                    </div>
                    <div class="content">{{ item.content ? item.content : locale._('default_rating_x',['x':item.rating]) }}</div>
                    <div class="footer">
                        <div class="left">
                            <div class="column">
                                <span class="time">{{ item.create_time|time_ago }}</span>
                            </div>
                            <div class="column like-column">
                                <span class="action review-like {{ like_class }}" data-url="{{ like_url }}">
                                    <i class="layui-icon layui-icon-praise"></i>
                                </span>
                                <span class="like-count" data-count="{{ item.like_count }}">{{ item.like_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
