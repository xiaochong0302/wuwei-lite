<form class="layui-form kg-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_logo') }}</label>
        <div class="layui-inline" style="width:40%;">
            <input class="layui-input" type="text" name="logo" value="{{ site.logo }}">
        </div>
        <div class="layui-inline">
            <button class="layui-btn" type="button" id="upload-logo">{{ locale._('upload') }}</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_favicon') }}</label>
        <div class="layui-inline" style="width:40%;">
            <input class="layui-input" type="text" name="favicon" value="{{ site.favicon }}">
        </div>
        <div class="layui-inline">
            <button class="layui-btn" type="button" id="upload-favicon">{{ locale._('upload') }}</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_title') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ site.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_url') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="url" value="{{ site.url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_copyright') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="copyright" value="{{ site.copyright }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_keywords') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="keywords" value="{{ site.keywords }}" placeholder="{{ locale._('keywords_tips') }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_description') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="description" value="{{ site.description }}">
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
