<form class="layui-form kg-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_timezone') }}</label>
        <div class="layui-input-block">
            <select name="timezone" lay-search="true" lay-verify="required">
                <option value="">{{ locale._('select') }}</option>
                {% for item in timezones %}
                    <option value="{{ item.code }}" {% if item.code == site.timezone %}selected="selected"{% endif %}>{{ item.name }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_language') }}</label>
        <div class="layui-input-block">
            <select name="language" lay-verify="required">
                <option value="">{{ locale._('select') }}</option>
                {% for value,title in languages %}
                    <option value="{{ value }}" {% if value == site.language %}selected="selected"{% endif %}>{{ title }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_currency') }}</label>
        <div class="layui-input-block">
            <select name="currency" lay-verify="required">
                <option value="">{{ locale._('select') }}</option>
                {% for value,item in currencies %}
                    <option value="{{ value }}" {% if value == site.currency %}selected="selected"{% endif %}>{{ item.title }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('site_status') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="online" title="{{ locale._('status_online') }}" lay-filter="status" {% if site.status == 'online' %}checked="checked"{% endif %}>
            <input type="radio" name="status" value="offline" title="{{ locale._('status_offline') }}" lay-filter="status" {% if site.status == 'offline' %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="offline-tips-block" style="{{ offline_tips_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('offline_tips') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="offline_tips" value="{{ site.offline_tips }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('allow_register') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="allow_register" value="1" title="{{ locale._('yes') }}" {% if site.allow_register == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="allow_register" value="0" title="{{ locale._('no') }}" {% if site.allow_register == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('enable_site_analytics') }}</label>
        <div class="layui-input-block">
            <input type="radio" name="analytics_enabled" value="1" title="{{ locale._('yes') }}" lay-filter="analytics" {% if site.analytics_enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="analytics_enabled" value="0" title="{{ locale._('no') }}" lay-filter="analytics" {% if site.analytics_enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="analytics-script-block" style="{{ analytics_script_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('analytics_script') }}</label>
            <div class="layui-input-block">
                <textarea name="analytics_script" class="layui-textarea" placeholder="{{ locale._('analytics_script_tips') }}">{{ site.analytics_script }}</textarea>
            </div>
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
