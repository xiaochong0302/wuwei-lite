<form class="layui-form kg-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('page_title') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ page.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('published') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="{{ locale._('yes') }}" {% if page.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="{{ locale._('no') }}" {% if page.published == 0 %}checked="checked"{% endif %}>
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
