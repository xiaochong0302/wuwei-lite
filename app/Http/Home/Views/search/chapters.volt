{% if pager.total_items > 0 %}
    <div class="search-course-list">
        {% for item in pager.items %}
            {% set chapter_url = url({'for':'home.chapter.show','id':item.id,'slug':item.slug}) %}
            <div class="search-course-card">
                <div class="left">
                    <div class="model">
                        <span class="layui-badge layui-bg-green">{{ chapter_model(item.model) }}</span>
                    </div>
                    <div class="cover">
                        <a href="{{ chapter_url }}" target="_blank">
                            <img src="{{ item.course.cover }}!cover_270" alt="{{ item.title|striptags }}">
                        </a>
                    </div>
                </div>
                <div class="right">
                    <div class="title layui-elip">
                        <a href="{{ chapter_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span>{{ locale._('lesson_users_x',['x':item.user_count|human_number]) }}</span>
                        <span>{{ locale._('lesson_likes_x',['x':item.like_count|human_number]) }}</span>
                        <span>{{ locale._('lesson_comments_x',['x':item.comment_count|human_number]) }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
{% else %}
    {{ partial('search/empty') }}
{% endif %}
