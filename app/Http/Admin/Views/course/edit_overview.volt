<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_details') }}</label>
        <div class="layui-input-block">
            <div id="vditor"></div>
            <textarea name="details" class="layui-hide" id="vditor-textarea">{{ course.details }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_summary') }}</label>
        <div class="layui-input-block">
            <textarea name="summary" class="layui-textarea">{{ course.summary }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('course_keywords') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="keywords" value="{{ course.keywords }}" placeholder="{{ locale._('keywords_tips') }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
        </div>
    </div>
</form>
