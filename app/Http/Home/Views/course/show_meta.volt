<div class="course-meta wrap">
    <div class="cover">
        <img src="{{ course.cover }}" alt="{{ course.title }}">
    </div>
    <div class="info">
        <p class="item">
            <label class="key colon">{{ locale._('course_level') }}</label>
            <span class="value">{{ course_level(course.level) }}</span>
            <label class="key colon">{{ locale._('course_rating') }}</label>
            <span class="value">{{ "%0.1f"|format(course.rating) }}</span>
        </p>
        <p class="item">
            <label class="key colon">{{ locale._('study_expiry') }}</label>
            <span class="value">{{ locale._('month_x',['x':course.study_expiry]) }}</span>
            <label class="key colon">{{ locale._('refund_expiry') }}</label>
            {% if course.refund_expiry > 0 %}
            <span class="value">{{ locale._('day_x',['x':course.refund_expiry]) }}</span>
            {% else %}
            <span class="value">{{ locale._('not_supported') }}</span>
            {% endif %}
        </p>
        <p class="item">
            <label class="key colon">{{ locale._('regular_price') }}</label>
            {% if course.regular_price == 0 %}
                <span class="value free">{{ locale._('course_free') }}</span>
            {% else %}
                <span class="value price">{{ course.regular_price|human_price }}</span>
            {% endif %}
            <label class="key colon">{{ locale._('vip_price') }}</label>
            {% if course.vip_price == 0 %}
                <span class="value free">{{ locale._('course_free') }}</span>
            {% else %}
                <span class="value price">{{ course.vip_price|human_price }}</span>
            {% endif %}
        </p>
        <p class="item">
            <span class="value">{{ locale._('course_users_x',['x':course.user_count]) }}</span>
            <span class="value">{{ locale._('course_reviews_x',['x':course.review_count]) }}</span>
            <span class="value">{{ locale._('course_favorites_x',['x':course.favorite_count]) }}</span>
        </p>
    </div>
    <div class="rating-stats">
        <div class="item">
            <span class="star" id="star5"></span>
            <span class="score">{{ '%0.0f'|format(course.rating_stat.star5.rate) }}%</span>
        </div>
        <div class="item">
            <span class="star" id="star4"></span>
            <span class="score">{{ '%0.0f'|format(course.rating_stat.star4.rate) }}%</span>
        </div>
        <div class="item">
            <span class="star" id="star3"></span>
            <span class="score">{{ '%0.0f'|format(course.rating_stat.star3.rate) }}%</span>
        </div>
        <div class="item">
            <span class="star" id="star2"></span>
            <span class="score">{{ '%0.0f'|format(course.rating_stat.star2.rate) }}%</span>
        </div>
        <div class="item">
            <span class="star" id="star1"></span>
            <span class="score">{{ '%0.0f'|format(course.rating_stat.star1.rate) }}%</span>
        </div>
    </div>
    <div class="rating">{{ "%0.1f"|format(course.rating) }}</div>
</div>
