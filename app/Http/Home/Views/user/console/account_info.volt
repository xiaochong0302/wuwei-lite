{% extends 'templates/main.volt' %}

{% block content %}

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
            </div>
        </div>
    </div>

{% endblock %}
