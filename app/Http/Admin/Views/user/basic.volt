<form class="layui-form kg-form">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('user_avatar') }}</label>
        <div class="layui-input-block">
            <img id="avatar" class="kg-avatar" src="{{ user.avatar }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('id') }}</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">{{ user.id }}</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('user_name') }}</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">{{ user.name }}</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('user_title') }}</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">{{ user.title }}</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('user_about') }}</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">{{ user.about }}</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('account_email') }}</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">{{ account.email }}</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('admin_role') }}</label>
        <div class="layui-input-block">
            {% for role in admin_roles %}
                <input type="radio" name="admin_role" value="{{ role.id }}" title="{{ role.name }}" disabled="disabled" {% if user.admin_role == role.id %}checked="checked"{% endif %}>
            {% endfor %}
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('edu_role') }}</label>
        <div class="layui-input-block">
            {% for value,title in edu_roles %}
                <input type="radio" name="edu_role" value="{{ value }}" title="{{ title }}" disabled="disabled" {% if user.edu_role == value %}checked="checked"{% endif %}>
            {% endfor %}
        </div>
    </div>
    {% if user.vip == 1 %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('vip_expiry') }}</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ date('Y-m-d H:i',user.vip_expiry_time) }}</div>
            </div>
        </div>
    {% endif %}
    {% if user.locked == 1 %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('lock_expiry') }}</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ date('Y-m-d H:i',user.lock_expiry_time) }}</div>
            </div>
        </div>
    {% endif %}
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <a class="layui-btn" href="{{ edit_url }}">{{ locale._('edit') }}</a>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
        </div>
    </div>
</form>
