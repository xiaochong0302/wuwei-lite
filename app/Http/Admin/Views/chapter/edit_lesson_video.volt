{% set action_url = url({'for':'admin.chapter.content','id':chapter.id}) %}

{% if upload.id > 0 and upload.deleted == 0 %}
    {% set upload_id = upload.id %}
    {% set upload_path = upload.path %}
{% else %}
    {% set upload_id = 0 %}
    {% set upload_path = '' %}
{% endif %}

<form id="video-form" class="layui-form kg-form" method="POST" action="{{ action_url }}">
    {% if upload_id > 0 %}
        <div class="layui-form-item" id="picker-block">
            <label class="layui-form-label">{{ locale._('video_file') }}</label>
            <div class="layui-input-inline" style="width:300px;">
                <input class="layui-input" type="text" value="{{ upload_path }}" readonly="readonly">
            </div>
            <div class="layui-inline">
                <span id="upload-btn">{{ locale._('upload') }}</span>
            </div>
        </div>
    {% else %}
        <div class="layui-form-item" id="picker-block">
            <label class="layui-form-label">{{ locale._('video_file') }}</label>
            <div class="layui-input-inline" style="width:300px;">
                <input class="layui-input" type="text" value="" readonly="readonly">
            </div>
            <div class="layui-inline">
                <span id="upload-btn">{{ locale._('upload') }}</span>
            </div>
        </div>
    {% endif %}
    <div class="layui-form-item layui-hide" id="upload-block">
        <label class="layui-form-label">{{ locale._('video_file') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="upload_path" value="{{ upload_path }}" readonly="readonly">
            <input type="hidden" name="upload_id" value="{{ upload_id }}">
        </div>
    </div>
    <div class="layui-form-item layui-hide" id="md5-progress-block">
        <label class="layui-form-label">{{ locale._('hash_progress') }}</label>
        <div class="layui-input-block">
            <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="md5-progress" style="top:10px;">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
    <div class="layui-form-item layui-hide" id="upload-progress-block">
        <label class="layui-form-label">{{ locale._('upload_progress') }}</label>
        <div class="layui-input-block">
            <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="upload-progress" style="top:10px;">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('video_duration') }}</label>
        <div class="layui-input-block">
            <div class="layui-inline">
                <select name="duration[hours]">
                    {% for value in 0..10 %}
                        {% set selected = value == duration.hours ? 'selected="selected"' : '' %}
                        <option value="{{ value }}" {{ selected }}>{{ locale._('hour_x',['x':value]) }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="layui-inline">
                <select name="duration[minutes]">
                    {% for value in 0..59 %}
                        {% set selected = value == duration.minutes ? 'selected="selected"' : '' %}
                        <option value="{{ value }}" {{ selected }}>{{ locale._('minute_x',['x':value]) }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="layui-inline">
                <select name="duration[seconds]">
                    {% for value in 0..59 %}
                        {% set selected = value == duration.seconds ? 'selected="selected"' : '' %}
                        <option value="{{ value }}" {{ selected }}>{{ locale._('second_x',['x':value]) }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
            <input type="hidden" name="section" value="cos">
        </div>
    </div>
</form>
