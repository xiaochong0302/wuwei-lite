<form class="layui-form kg-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('package_cover') }}</label>
        <div class="layui-input-inline">
            <img id="img-cover" class="kg-package-cover" src="{{ package.cover }}">
            <input type="hidden" name="cover" value="{{ package.cover }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <button id="change-cover" class="layui-btn layui-btn-sm" type="button">{{ locale._('upload') }}</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('package_title') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ package.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('package_summary') }}</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="summary">{{ package.summary }}</textarea>
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
