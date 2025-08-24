<div class="layui-card layui-text">
    <div class="layui-card-header">{{ locale._('app_info') }}</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col width="40%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td>{{ locale._('app_version') }}</td>
                <td>{{ app_info.alias }} {{ app_info.version }}</td>
            </tr>
            <tr>
                <td>{{ locale._('license_domain') }}</td>
                <td>{{ license_info.server_host }}</td>
            </tr>
            {% if license_info.auth_type == 'user_count' %}
                <tr>
                    <td>{{ locale._('license_user_limit') }}</td>
                    <td>{{ license_info.user_count }}</td>
                </tr>
            {% else %}
                <tr>
                    <td>{{ locale._('license_expiry') }}</td>
                    <td>{{ date('Y-m-d H:i',license_info.expire_time) }}</td>
                </tr>
            {% endif %}
            </tbody>
        </table>
    </div>
</div>
