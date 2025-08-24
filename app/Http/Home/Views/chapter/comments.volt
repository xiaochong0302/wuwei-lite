{% if pager.total_items > 0 %}
    {% for item in pager.items %}
        {% set like_url = url({'for':'home.comment.like','id':item.id}) %}
        {% set delete_url = url({'for':'home.comment.delete','id':item.id}) %}
        {% set reply_url = url({'for':'home.comment.reply','id':item.id}) %}
        {% set reply_list_url = url({'for':'home.comment.replies','id':item.id},{'limit':5}) %}
        {% set like_class = item.me.liked == 1 ? 'liked' : '' %}
        {% set vip_class = item.owner.vip == 1 ? 'vip' : '' %}
        <div class="comment-box" id="comment-{{ item.id }}">
            <div class="comment-card">
                <div class="avatar">
                    <img src="{{ item.owner.avatar }}!avatar_160" alt="{{ item.owner.name }}">
                </div>
                <div class="info">
                    <div class="user">
                        <span class="name">@{{ item.owner.name }}</span>
                    </div>
                    <div class="content">{{ item.content }}</div>
                    <div class="footer">
                        <div class="left">
                            <div class="column">
                                <span class="time" title="{{ date('Y-m-d H:i:s',item.create_time) }}">{{ item.create_time|time_ago }}</span>
                            </div>
                            <div class="column like-column">
                                <span class="action comment-like {{ like_class }}" data-url="{{ like_url }}">
                                    <i class="layui-icon layui-icon-praise"></i>
                                </span>
                                <span class="like-count" data-count="{{ item.like_count }}">{{ item.like_count }}</span>
                            </div>
                            {% if item.reply_count > 0 %}
                                <div class="column action comment-toggle" data-id="{{ item.id }}" data-url="{{ reply_list_url }}">
                                    <span class="toggle-tips"><i class="layui-icon layui-icon-down"></i></span>
                                    <span class="reply-count" data-count="{{ item.reply_count }}">{{ item.reply_count }}</span>
                                    <span>{{ locale._('comment_replies') }}</span>
                                </div>
                            {% endif %}
                        </div>
                        <div class="right">
                            <div class="column">
                                <span class="action comment-reply" data-id="{{ item.id }}">{{ locale._('reply') }}</span>
                            </div>
                            {% if item.me.owned == 1 %}
                                <div class="column">
                                    <span class="action comment-delete" data-id="{{ item.id }}" data-url="{{ delete_url }}">{{ locale._('delete') }}</span>
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
                            <button class="layui-btn layui-btn-sm" lay-submit="true" lay-filter="reply_comment" data-comment-id="{{ item.id }}"
                                    data-parent-id="{{ item.parent_id }}">{{ locale._('submit') }}</button>
                            <button class="layui-btn layui-btn-sm layui-btn-primary reply-cancel" type="button" data-id="{{ item.id }}">{{ locale._('cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="reply-list" id="reply-list-{{ item.id }}" style="display:none;"></div>
        </div>
    {% endfor %}
    {{ partial('partials/pager_ajax') }}
{% endif %}
