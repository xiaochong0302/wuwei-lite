<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.update','id':chapter.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('chapter_title') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ chapter.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('chapter_summary') }}</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="summary">{{ chapter.summary }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('chapter_keywords') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="keywords" value="{{ chapter.keywords }}" placeholder="{{ locale._('keywords_tips') }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('sort_order') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="priority" value="{{ chapter.priority }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('enable_comment') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="comment_enabled" value="1" title="{{ locale._('yes') }}" {% if chapter.comment_enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="comment_enabled" value="0" title="{{ locale._('no') }}" {% if chapter.comment_enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('published') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if chapter.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if chapter.published == 0 %}checked="checked"{% endif %}>
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
