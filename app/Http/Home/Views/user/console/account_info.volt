{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro connect_provider(item) %}
        {% if item.provider == 1 %}
            <i class="iconfont icon-google login-google"></i>
        {% elseif item.provider == 2 %}
            <i class="iconfont icon-facebook login-facebook"></i>
        {% elseif item.provider == 3 %}
            <i class="iconfont icon-github login-github"></i>
        {% endif %}
    {%- endmacro %}

    {%- macro connect_user(item) %}
        {% if item.open_email %}
            <span class="open-email">{{ item.open_email }}</span>
        {% else %}
            <span class="open-name">{{ item.open_name }}</span>
        {% endif %}
    {%- endmacro %}

    {% set edit_pwd_url = url({'for':'home.uc.account'},{'type':'password'}) %}
    {% set edit_email_url = url({'for':'home.uc.account'},{'type':'email'}) %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="section">
                    <div class="my-nav">
                        <span class="title">{{ locale._('my_account') }}</span>
                    </div>
                    <div class="security-item-list">
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                                <span class="title">{{ locale._('password') }}</span>
                                <span class="summary">{{ locale._('change_pwd_tips') }}</span>
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm" href="{{ edit_pwd_url }}">{{ locale._('edit') }}</a>
                            </div>
                        </div>
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                                <span class="title">{{ locale._('email') }}</span>
                                <span class="summary">{{ account.email|anonymous }}</span>
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm" href="{{ edit_email_url }}">{{ locale._('edit') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="my-nav">
                        <span class="title">{{ locale._('external_logins') }}</span>
                    </div>
                    {% if connects|length > 0 %}
                        <div class="connect-tips">{{ locale._('linked_accounts') }}</div>
                        <div class="connect-list">
                            <table class="layui-table" lay-skin="line">
                                <tr>
                                    <td>{{ locale._('connect_provider') }}</td>
                                    <td>{{ locale._('connect_user') }}</td>
                                    <td>{{ locale._('connect_last_login') }}</td>
                                    <td width="15%">{{ locale._('actions') }}</td>
                                </tr>
                                {% for connect in connects %}
                                    {% set delete_url = url({'for':'home.uc.delete_connect','id':connect.id}) %}
                                    {% set active_time = connect.update_time > 0 ? connect.update_time : connect.create_time %}
                                    <tr>
                                        <td>{{ connect_provider(connect) }}</td>
                                        <td>{{ connect_user(connect) }}</td>
                                        <td>{{ date('Y-m-d H:i',active_time) }}</td>
                                        <td><a class="layui-btn layui-btn-danger layui-btn-sm kg-delete" href="javascript:" data-url="{{ delete_url }}">{{ locale._('delete') }}</a></td>
                                    </tr>
                                {% endfor %}
                            </table>
                        </div>
                    {% endif %}
                    <div class="connect-tips">{{ locale._('available_providers') }}</div>
                    <div class="oauth-list">
                        {% if oauth_provider.google.enabled == 1 %}
                            <a class="iconfont icon-google login-google" title="google" href="{{ url({'for':'home.oauth.google'}) }}"></a>
                        {% endif %}
                        {% if oauth_provider.github.enabled == 1 %}
                            <a class="iconfont icon-github login-github" title="github" href="{{ url({'for':'home.oauth.github'}) }}"></a>
                        {% endif %}
                        {% if oauth_provider.facebook.enabled == 1 %}
                            <a class="iconfont icon-facebook login-facebook" title="facebook" href="{{ url({'for':'home.oauth.facebook'}) }}"></a>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}
