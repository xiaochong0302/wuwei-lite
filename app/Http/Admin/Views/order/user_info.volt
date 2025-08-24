<fieldset class="layui-elem-field layui-field-title">
    <legend>{{ locale._('customer_info') }}</legend>
</fieldset>
<br>
<table class="kg-table layui-table">
    <tr>
        <th>{{ locale._('id') }}</th>
        <th>{{ locale._('user_name') }}</th>
        <th>{{ locale._('account_email') }}</th>
        <th>{{ locale._('create_time') }}</th>
        <th>{{ locale._('active_time') }}</th>
    </tr>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td><a href="mailto:{{ account.email }}">{{ account.email }}</a></td>
        <td>{{ date('Y-m-d H:i',user.create_time) }}</td>
        <td>{{ date('Y-m-d H:i',user.active_time) }}</td>
    </tr>
</table>
<br>
<div class="kg-center">
    <button class="layui-btn layui-btn-primary kg-back">{{ locale._('back') }}</button>
</div>
