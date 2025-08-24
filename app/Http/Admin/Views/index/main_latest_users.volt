<div class="layui-card layui-text">
    <div class="layui-card-header">{{ locale._('latest_users') }}</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th>{{ locale._('id') }}</th>
                <th>{{ locale._('user_name') }}</th>
                <th>{{ locale._('email') }}</th>
                <th>{{ locale._('create_time') }}</th>
                <th>{{ locale._('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            {% for user in latest_users %}
                {% set show_url = url({'for':'admin.user.show','id':user.id}) %}
                <tr>
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.account.email }}</td>
                    <td>{{ date('Y-m-d H:i',user.create_time) }}</td>
                    <td class="center">
                        <a class="layui-btn layui-btn-sm" href="{{ show_url }}">{{ locale._('details') }}</a>
                    </td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>
