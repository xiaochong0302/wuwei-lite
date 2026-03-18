{% set res_list_url = url({'for':'admin.course.resources','id':course.id}) %}

<div id="res-list" data-url="{{ res_list_url }}"></div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>{{ locale._('upload_resource') }}</legend>
</fieldset>

<form class="layui-form kg-form" id="res-form">
    <div class="layui-form-item" id="res-upload-block">
        <div class="layui-input-block">
            <span id="res-upload-btn">{{ locale._('upload') }}</span>
        </div>
    </div>
    <div class="layui-form-item layui-hide" id="res-md5-progress-block">
        <label class="layui-form-label">{{ locale._('hash_progress') }}</label>
        <div class="layui-input-block">
            <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="res-md5-progress" style="top:10px;">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
    <div class="layui-form-item layui-hide" id="res-upload-progress-block">
        <label class="layui-form-label">{{ locale._('upload_progress') }}</label>
        <div class="layui-input-block">
            <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="res-upload-progress" style="top:10px;">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
    <div class="layui-hide">
        <input type="hidden" name="course_id" value="{{ course.id }}">
    </div>
</form>
