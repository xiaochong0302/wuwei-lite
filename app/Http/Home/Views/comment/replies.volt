{% if pager.total_items > 0 %}
    {% for item in pager.items %}
        {% set like_url = url({'for':'home.comment.like','id':item.id}) %}
        {% set delete_url = url({'for':'home.comment.delete','id':item.id}) %}
        {% set reply_url = url({'for':'home.comment.reply','id':item.id}) %}
        <div class="comment-box" id="comment-{{ item.id }}">
            <div class="comment-card">
                <div class="avatar">
                    <img src="{{ item.owner.avatar }}!avatar_160" alt="{{ item.owner.name }}">
                </div>
                <div class="info">
                    <div class="user">
                        <span class="name">@{{ item.owner.name }}</span>
                        {% if item.to_user.id is defined %}
                            <span class="separator">→</span>
                            <span class="name">@{{ item.to_user.name }}</span>
                        {% endif %}
                    </div>
                    <div class="content">{{ item.content }}</div>
                    <div class="footer">
                        <div class="left">
                            <div class="column">
                                <span class="time">{{ item.create_time|time_ago }}</span>
                            </div>
                            <div class="column like-column">
                                <span class="action comment-like {{ like_class }}" data-url="{{ like_url }}">
                                    <i class="layui-icon layui-icon-praise"></i>
                                </span>
                                <span class="like-count" data-count="{{ item.like_count }}">{{ item.like_count }}</span>
                            </div>
                        </div>
                        <div class="right">
                            <div class="column">
                                <span class="action comment-reply" data-id="{{ item.id }}">{{ locale._('reply') }}</span>
                            </div>
                            {% if item.owner.id == auth_user.id %}
                                <div class="column">
                                    <span class="action comment-delete" data-id="{{ item.id }}" data-parent-id="{{ item.parent_id }}" data-url="{{ delete_url }}">{{ locale._('delete') }}</span>
                                </div>
                            {% endif %}
                        </div>
                    </div>
                </div>
            </div>
            <div class="comment-form" id="comment-form-{{ item.id }}" style="display:none;">
                <form class="layui-form" method="POST" action="{{ reply_url }}">
                    <textarea class="layui-textarea" name="content" placeholder="{{ locale._('join_discussion_tips') }}" lay-verify="required"></textarea>
                    <div class="footer">
                        <div class="toolbar"></div>
                        <div class="action">
                            <button class="layui-btn layui-btn-sm" lay-submit="true" lay-filter="reply_comment" data-comment-id="{{ item.id }}" data-parent-id="{{ item.parent_id }}">{{ locale._('submit') }}</button>
                            <button class="layui-btn layui-btn-sm layui-btn-primary reply-cancel" type="button" data-id="{{ item.id }}">{{ locale._('cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    {% endfor %}
    {{ partial('partials/pager_ajax') }}
{% endif %}
