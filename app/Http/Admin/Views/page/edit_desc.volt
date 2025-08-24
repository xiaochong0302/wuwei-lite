<form class="layui-form kg-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <div id="vditor"></div>
            <textarea name="content" class="layui-hide" id="vditor-textarea">{{ page.content }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
        </div>
    </div>
</form>
