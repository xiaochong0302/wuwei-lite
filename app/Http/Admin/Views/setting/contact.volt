{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.contact'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ locale._('contact_settings') }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('enable') }}</label>
            <div class="layui-input-block">
                <input type="radio" name="enabled" value="1" title="{{ locale._('yes') }}" {% if contact.enabled == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="enabled" value="0" title="{{ locale._('no') }}" {% if contact.enabled == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_twitter') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="twitter" value="{{ contact.twitter }}" placeholder="https://www.twitter.com/{username}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_facebook') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="facebook" value="{{ contact.facebook }}" placeholder="https://www.facebook.com/{username}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_youtube') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="youtube" value="{{ contact.youtube }}" placeholder="https://www.youtube.com/@{handle}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_reddit') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="reddit" value="{{ contact.reddit }}" placeholder="https://www.reddit.com/user/{username}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_linkedin') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="linkedin" value="{{ contact.linkedin }}" placeholder="https://www.linkedin.com/in/{username}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_phone') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="phone" value="{{ contact.phone }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_email') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="email" value="{{ contact.email }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('contact_address') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="address" value="{{ contact.address }}">
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

{% endblock %}
