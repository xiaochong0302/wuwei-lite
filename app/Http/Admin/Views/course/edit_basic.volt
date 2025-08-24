<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_cover') }}</label>
        <div class="layui-input-inline">
            <img id="img-cover" class="kg-course-cover" src="{{ course.cover }}">
            <input type="hidden" name="cover" value="{{ course.cover }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <button id="change-cover" class="layui-btn layui-btn-sm" type="button">{{ locale._('upload') }}</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_title') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ course.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_category') }}</label>
        <div class="layui-input-block">
            <select name="category_id" lay-search="true">
                <option value="">{{ locale._('select') }}</option>
                {% for option in category_options %}
                    {% set selected = course.category_id == option.id ? 'selected="selected"' : '' %}
                    <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_teacher') }}</label>
        <div class="layui-input-block">
            <select name="teacher_id" lay-search="true">
                <option value="">{{ locale._('select') }}</option>
                {% for option in teacher_options %}
                    {% set selected = course.teacher_id == option.id ? 'selected="selected"' : '' %}
                    <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_level') }}</label>
        <div class="layui-input-block">
            <select name="level" lay-search="true">
                <option value="">{{ locale._('select') }}</option>
                {% for value,title in level_types %}
                    {% set selected = course.level == value ? 'selected="selected"' : '' %}
                    <option value="{{ value }}" {{ selected }}>{{ title }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('enable_review') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="review_enabled" value="1" title="{{ locale._('yes') }}" {% if course.review_enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="review_enabled" value="0" title="{{ locale._('no') }}" {% if course.review_enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('enable_comment') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="comment_enabled" value="1" title="{{ locale._('yes') }}" {% if course.comment_enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="comment_enabled" value="0" title="{{ locale._('no') }}" {% if course.comment_enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('featured') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="featured" value="1" title="{{ locale._('yes') }}" {% if course.featured == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="featured" value="0" title="{{ locale._('no') }}" {% if course.featured == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('published') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if course.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if course.published == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
        </div>
    </div>
</form>
