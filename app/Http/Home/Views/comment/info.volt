{% set delete_url = url({'for':'home.comment.delete','id':comment.id}) %}

{% if comment.parent_id == 0 %}
    <div class="comment-box" id="comment-{{ comment.id }}">
        <div class="comment-card">
            <div class="avatar">
                <img src="{{ comment.owner.avatar }}!avatar_160" alt="{{ comment.owner.name }}">
            </div>
            <div class="info">
                <div class="user">
                    <span class="name">{{ comment.owner.name }}</span>
                </div>
                <div class="content">{{ comment.content }}</div>
                <div class="footer">
                    <div class="left">
                        <div class="column">
                            <span class="time">{{ comment.create_time|time_ago }}</span>
                        </div>
                    </div>
                    <div class="right">
                        <div class="column">
                            <span class="action comment-delete" data-id="{{ comment.id }}" data-url="{{ delete_url }}">{{ locale._('delete') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endif %}

{% if comment.parent_id > 0 %}
    <div class="comment-card">
        <div class="avatar">
            <img src="{{ comment.owner.avatar }}!avatar_160" alt="{{ comment.owner.name }}">
        </div>
        <div class="info">
            <div class="user">
                <span class="name">@{{ comment.owner.name }}</span>
                {% if comment.to_user.id is defined %}
                    <span class="separator">→</span>
                    <span>@{{ comment.to_user.name }}</span>
                {% endif %}
            </div>
            <div class="content">{{ comment.content }}</div>
            <div class="footer">
                <div class="left">
                    <span class="column">{{ comment.create_time|time_ago }}</span>
                </div>
                <div class="right">
                    <span class="column">
                        <span class="action comment-delete" data-id="{{ comment.id }}" data-url="{{ delete_url }}">{{ locale._('delete') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
{% endif %}
